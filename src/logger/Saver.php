<?php
namespace LaravelAPM\logger;


use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Config;
use LaravelAPM\logger\storage\File;
use LaravelAPM\logger\storage\Elastic;

/**
 * A small factory to handle creation of the profile saver instance.
 *
 * This class only exists to handle cases where an incompatible version of pimple
 * exists in the host application.
 */
class Saver
{
    /**
     * Get a saver instance based on configuration data.
     *
     * @return File|Elastic
     */
    public static function factory()
    {
        $config = Config::get('laravelAPM');
        switch ($config['save.handler']) {
            case 'file':
                return new File($config['save.handler.filename']);
            case 'elastic':
            default:
                $client = ClientBuilder::create()->setHosts($config['hosts'])->build();
                return new Elastic($client);
        }
    }
}
