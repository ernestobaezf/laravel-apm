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
            $config = Config::all();
            $bulkParams = [];
            foreach ($dataArray as $fieldArr) {
                $bulkParams['body'][] = [
                    'index' => [
                        '_index' => $config['es.index'],
                        '_type' => $config['es.type']
                    ]
                ];
                $bulkParams['body'][] = $fieldArr;
            }

            $this->client->bulk($bulkParams);
        } catch (Exception $ex) {
            error_log('xhgui indexing - ' . $ex->getMessage());
        }
    }

}
