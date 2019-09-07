<?php
namespace LaravelAPM\external;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Config;
use LaravelAPM\logger\mapper\ProfileMapping;

class Indexer
{
    public static function createIndex()
    {
        $config = Config::all();
        $profileMapping = new ProfileMapping();
        $params = [
            'index' => $config['laravel_apm.es.index'],
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                ],
                'mappings' => [
                    $config['laravel_apm.es.type'] => $profileMapping->getMapping()
                ],
            ],
        ];
        try {
            $esClient = ClientBuilder::create()->setHosts($config['laravel_apm.hosts'])->build();
            $res = $esClient->indices()->create($params);
            echo json_encode($res);
        } catch (\Exception $e) {
            error_log('xhgui create index - ' . $e->getMessage());
        }
    }
}

Indexer::createIndex();
