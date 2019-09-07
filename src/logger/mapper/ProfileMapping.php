<?php
namespace LaravelAPM\logger\mapper;

use Elasticsearch\ClientBuilder;

/**
 * Description of ProfileMpping
 *
 * @author nishant
 */
class ProfileMapping
{

    const KEY_TRANSACTION_ID = 'TRANSACTION_ID';
    const KEY_URL = 'URL';
    const KEY_SERVER = 'SERVER';
    const KEY_SIMPLE_URL = 'SIMPLE_URL';
    const KEY_REQUEST_TIME_SEC = 'REQUEST_TS';
    const KEY_REQUEST_TIME_MICRO_SEC = 'REQUEST_MICRO_SEC';
    const KEY_REQUEST_DATE = 'REQUEST_DATE';
    const KEY_CALLER = 'CALLER_METHOD';
    const KEY_CALLEE = 'CALLEE_METHOD';
    const KEY_CALL_COUNTS_TO_CALLEE = 'CT';
    const KEY_TIME_IN_CALLEE = 'WT';
    const KEY_CPU_TIME_IN_CALLEE = 'CPU';
    const KEY_CHANGE_IN_MEM_USAGE = 'MU';
    const KEY_CHANGE_IN_PEAK_MEM_USAGE = 'PMU';

    private $mapping = [
        'dynamic' => true,
        'date_detection' => false,
        '_all' => ['enabled' => false],
        '_source' => ['enabled' => true],
        'properties' => [
            self::KEY_TRANSACTION_ID => ['type' => 'string', 'index' => 'not_analyzed'],
            self::KEY_URL => ['type' => 'string', 'index' => 'not_analyzed'],
            self::KEY_SERVER => ['type' => 'string', 'index' => 'not_analyzed'],
            self::KEY_SIMPLE_URL => ['type' => 'string', 'index' => 'not_analyzed'],
            self::KEY_REQUEST_TIME_SEC => ['type' => 'long'],
            self::KEY_REQUEST_TIME_MICRO_SEC => ['type' => 'double'],
            self::KEY_REQUEST_DATE => ['type' => 'date', 'format' => 'YYYY-MM-dd HH:mm:ss'],
            self::KEY_CALLER => ['type' => 'string', 'index' => 'not_analyzed'],
            self::KEY_CALLEE => ['type' => 'string', 'index' => 'not_analyzed'],
            self::KEY_CALL_COUNTS_TO_CALLEE => ['type' => 'integer'],
            self::KEY_TIME_IN_CALLEE => ['type' => 'double'],
            self::KEY_CPU_TIME_IN_CALLEE => ['type' => 'double'],
            self::KEY_CHANGE_IN_MEM_USAGE => ['type' => 'double'],
            self::KEY_CHANGE_IN_PEAK_MEM_USAGE => ['type' => 'double'],
        ],
    ];

    public function getMapping() {
        return $this->mapping;
    }

}
