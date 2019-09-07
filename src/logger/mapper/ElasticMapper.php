<?php
namespace LaravelAPM\logger\mapper;

/**
 * Description of ElasticMapper
 *
 * @author nishant
 */
class ElasticMapper
{

    const KEY_PROFILE_DATA = 'profile';
    const KEY_URL = 'url';
    const KEY_SERVER = 'server';
    const KEY_SIMPLE_URL = 'simple_url';
    const KEY_REQUEST_TIME_SEC = 'request_ts';
    const KEY_REQUEST_TIME_MICRO_SEC = 'request_ts_micro';
    const KEY_REQUEST_DATE = 'request_date';

    private $rawData;

    public function __construct($data) {
        $this->rawData = $data;
    }

    public function getRawData() {
        return $this->rawData;
    }

    public function getFromMetaData($key) {
        return $this->rawData['meta'][$key];
    }

    public function getProfile() {
        return $this->rawData[self::KEY_PROFILE_DATA];
    }

    public function getUrl() {
        return $this->getFromMetaData(self::KEY_URL);
    }

    public function getServer() {
        return $this->getFromMetaData(self::KEY_SERVER);
    }

    public function getSimpleUrl() {
        return $this->getFromMetaData(self::KEY_SIMPLE_URL);
    }

    public function getRequestTs() {
        return $this->getFromMetaData(self::KEY_REQUEST_TIME_SEC);
    }

    public function getRequestTsMicro() {
        return $this->getFromMetaData(self::KEY_REQUEST_TIME_MICRO_SEC);
    }

    public function getRequestDate() {
        return $this->getFromMetaData(self::KEY_REQUEST_DATE);
    }

    public function getMetricsDataArray() {
        $profileArray = $this->getProfile();
        $metricsDataArray = [];
        foreach ($profileArray as $callerCallee => $dataReadings) {
            $callerCalleeArr = explode('==>', $callerCallee);
            $caller = $callerCalleeArr[0];
            $callee = (count($callerCalleeArr) > 1) ? $callerCalleeArr[1] : null;
            $metricsDataArray[] = [
                ProfileMapping::KEY_CALLER => $caller,
                ProfileMapping::KEY_CALLEE => $callee,
                ProfileMapping::KEY_CALL_COUNTS_TO_CALLEE => $dataReadings['ct'],
                ProfileMapping::KEY_TIME_IN_CALLEE => $dataReadings['wt'],
                ProfileMapping::KEY_CPU_TIME_IN_CALLEE => $dataReadings['cpu'],
                ProfileMapping::KEY_CHANGE_IN_MEM_USAGE => $dataReadings['mu'],
                ProfileMapping::KEY_CHANGE_IN_PEAK_MEM_USAGE => $dataReadings['pmu'],
            ];
        }
        return $metricsDataArray;
    }

    public function getProfilingDataArray() {
        $dataArr = [];
        $metaDataArr[ProfileMapping::KEY_TRANSACTION_ID] = uniqid();
        $metaDataArr[ProfileMapping::KEY_URL] = $this->getUrl();
        $metaDataArr[ProfileMapping::KEY_SERVER] = $this->getServer();
        $metaDataArr[ProfileMapping::KEY_SIMPLE_URL] = $this->getSimpleUrl();
        $metaDataArr[ProfileMapping::KEY_REQUEST_TIME_SEC] = $this->getRequestTs();
        $metaDataArr[ProfileMapping::KEY_REQUEST_TIME_MICRO_SEC] = $this->getRequestTsMicro();
        $metaDataArr[ProfileMapping::KEY_REQUEST_DATE] = $this->getRequestDate();
        $profileDataArray = $this->getMetricsDataArray();
        foreach ($profileDataArray as $profileData) {
            $dataArr[] = array_merge($metaDataArr, $profileData);
        }
        return $dataArr;
    }

}
