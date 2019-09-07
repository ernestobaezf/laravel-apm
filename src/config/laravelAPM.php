<?php
/**
 * Default configuration for TwLogger
 */
return array(
    'debug' => false,
    'mode' => 'development',

    // Can be either elastic or file.
    /* 
    'save.handler' => 'file',
    'save.handler.filename' => dirname(__DIR__) . '/cache/' . 'xhgui.data.' . microtime(true) . '_' . substr(md5($url), 0, 6),
    */
    'save.handler' => 'elastic',

    // Needed for file save handler. Beware of file locking. You can adujst this file path 
    // to reduce locking problems (eg uniqid, time ...)
    //'save.handler.filename' => __DIR__.'/../data/xhgui_'.date('Ymd').'.dat',
    'hosts' => [
        [
            'host' => 'localhost',
            'port' => 9200
        ]
    ],

    'es.index' => 'twprofile',
    'es.type' => 'profile',

    // Profile 1 in 100 requests.
    // You can return true to profile every request.
    'profiler.enable' => function() {
        return rand(1, 100) === 42;
    },

    'profiler.simple_url' => function($url) {
        return preg_replace('/\=\d+/', '', $url);
    }

);
