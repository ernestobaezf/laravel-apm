<?php
namespace LaravelAPM\logger;


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
        switch (Config::get('laravel_apm.save.handler')) {
            case 'file':
                return new File(Config::get('laravel_apm.save.handler.filename'));
            case 'elastic':
            default:
                $client = Elasticsearch\ClientBuilder::create()->setHosts(Config::get('laravel_apm.hosts'))->build();
                return new Elastic($client);
        }
    }
}
