<?php
namespace LaravelAPM\logger\storage;

use Exception;
use Elasticsearch\Client;
use Illuminate\Support\Facades\Config;
use LaravelAPM\logger\mapper\ElasticMapper;

/**
 * Description of Elastic
 *
 * @author nishant
 */
class Elastic implements StorageInterface
{
    private $client;

    public function __construct(Client $elasticClient) {
        $this->client = $elasticClient;
    }

    public function save($data) {
        $dataMapper = new ElasticMapper($data);
        $dataArray = $dataMapper->getProfilingDataArray();
        if (empty($dataArray)) {
            return;
        }
        try {
            $bulkParams = [];
            foreach ($dataArray as $fieldArr) {
                $bulkParams['body'][] = [
                    'index' => [
                        '_index' => Config::get('laravelAPM.es.index'),
                        '_type' => Config::get('laravelAPM.es.type')
                    ]
                ];
                $bulkParams['body'][] = $fieldArr;
            }

            $this->client->bulk($bulkParams);
        } catch (Exception $ex) {
            report($ex);
        }
    }

}
