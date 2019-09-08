<?php
/**
 * User: Ernesto Baez
 */

namespace LaravelAPM\Http\Middleware;


use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use LaravelAPM\logger\Saver;
use LaravelAPM\logger\Util;

class RecordTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->startRecord();

        $response = $next($request);

        $this->finishRecord();

        return $response;
    }

    private function startRecord()
    {
        if (!extension_loaded('xhprof') && !extension_loaded('uprofiler') && !extension_loaded('tideways_xhprof')) {
            error_log('xhgui - either extension xhprof, uprofiler or tideways must be loaded');
            return;
        }

        if (!isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
        } else if (extension_loaded('uprofiler')) {
            uprofiler_enable(UPROFILER_FLAGS_CPU | UPROFILER_FLAGS_MEMORY);
        } else if (extension_loaded('tideways_xhprof')) {
            tideways_xhprof_enable(TIDEWAYS_XHPROF_FLAGS_CPU | TIDEWAYS_XHPROF_FLAGS_MEMORY | TIDEWAYS_XHPROF_FLAGS_NO_BUILTINS);
        } else {
            if (PHP_MAJOR_VERSION == 5 && PHP_MINOR_VERSION > 4) {
                xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_NO_BUILTINS);
            } else {
                xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
            }
        }
    }

    private function finishRecord()
    {
        if (extension_loaded('uprofiler')) {
            $data['profile'] = uprofiler_disable();
        } else if (extension_loaded('tideways_xhprof')) {
            $data['profile'] = tideways_xhprof_disable();
        } else {
            $data['profile'] = xhprof_disable();
        }

        // ignore_user_abort(true) allows your PHP script to continue executing, even if the user has terminated their request.
        // Further Reading: http://blog.preinheimer.com/index.php?/archives/248-When-does-a-user-abort.html
        // flush() asks PHP to send any data remaining in the output buffers. This is normally done when the script completes, but
        // since we're delaying that a bit by dealing with the xhprof stuff, we'll do it now to avoid making the user wait.
        ignore_user_abort(true);
        flush();

        $uri = array_key_exists('REQUEST_URI', $_SERVER)
            ? $_SERVER['REQUEST_URI']
            : null;
        if (empty($uri) && isset($_SERVER['argv'])) {
            $cmd = basename($_SERVER['argv'][0]);
            $uri = $cmd . ' ' . implode(' ', array_slice($_SERVER['argv'], 1));
        }

        $time = array_key_exists('REQUEST_TIME', $_SERVER)
            ? $_SERVER['REQUEST_TIME']
            : time();
        // In some cases there is comma instead of dot
        $delimiter = (strpos($_SERVER['REQUEST_TIME_FLOAT'], ',') !== false) ? ',' : '.';
        $requestTimeFloat = explode($delimiter, $_SERVER['REQUEST_TIME_FLOAT']);
        if (!isset($requestTimeFloat[1])) {
            $requestTimeFloat[1] = 0;
        }

        $config = Config::get('laravelAPM');
        if ($config["save.handler"] === 'file') {
            $requestTs = array('sec' => $time, 'usec' => 0);
            $requestTsMicro = array('sec' => $requestTimeFloat[0], 'usec' => $requestTimeFloat[1]);
        } else {
            $requestTs = $time;
            $requestTsMicro = implode('.', $requestTimeFloat);
        }

        $data['meta'] = array(
            'url' => $uri,
            'server' => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '',
            'simple_url' => Util::simpleUrl($uri),
            'request_ts' => $requestTs,
            'request_ts_micro' => $requestTsMicro,
            'request_date' => date('Y-m-d H:i:s', $time),
        );

        try {
            $saver = Saver::factory();
            $saver->save($data);
        } catch (Exception $e) {
            report($e);
        }
    }
}

