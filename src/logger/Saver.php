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
        switch (Config::get('laravelAPM.save.handler')) {
            case 'file':
                return new File(Config::get('laravelAPM.save.handler.filename'));
            case 'elastic':
            default:
                $client = ClientBuilder::create()->setHosts(Config::get('laravelAPM.hosts'))->build();
                return new Elastic($client);
        }
    }
}
