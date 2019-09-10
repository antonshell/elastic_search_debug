<?php

namespace src;

use src\Index\IndexInterface;

/**
 * Class ElasticClient
 * @package src
 */
class ElasticClient
{
    const METHOD_SAVE = 'save';

    private $curl;

    private $config = [];

    /**
     * ElasticClient constructor.
     */
    public function __construct() {
        $this->curl = new Curl;
        $this->config = require __DIR__ . '/../_config.php';
    }

    /**
     * @return array
     */
    public function testConnection(){
        return $this->curl->sendRequest($this->getBaseUrl());
    }

    /**
     * @param IndexInterface $index
     * @param array $data
     * @param bool $ingest
     * @return array|mixed
     * @throws \Exception
     */
    public function saveItem(IndexInterface $index, array $data, $ingest = false){
        $entity = $index->getIndexName();
        $mappingName = $index->getMappingName();
        $url = $this->getBaseUrl() . '/' . $entity . '/' . $mappingName . '/' . $data['id'];
        if($ingest){
            $url .= '?pipeline=attachment';
        }

        $data = $this->curl->sendRequest($url, 'PUT', $data,$this->getHeaders());
        $this->checkResponse($data, self::METHOD_SAVE, $entity);
        return $data;
    }

    /**
     * @param IndexInterface $index
     * @throws \Exception
     */
    public function rebuildIndex(IndexInterface $index){
        $this->deleteIndex($index);
        $this->createIndex($index);
    }

    /**
 * @param IndexInterface $index
 * @throws \Exception
 */
    public function createIndex(IndexInterface $index){
        $name = $index->getIndexName();
        $mapping = $index->getMappingName();

        $settings = $index->getSettings();
        $properties = $index->getProperties();

        // create index
        $url = $this->getBaseUrl() . '/' . $name;
        $data = $this->curl->sendRequest($url, 'PUT', $settings,$this->getHeaders());

        if(!isset($data['acknowledged'])){
            print_r($data);
            throw new \Exception('Cant create index ' . $name);
        }
    }

    /**
     * @param IndexInterface $index
     * @throws \Exception
     */
    public function createMapping(IndexInterface $index){
        $name = $index->getIndexName();
        $mapping = $index->getMappingName();
        $properties = $index->getProperties();

        // create mapping
        $url = $this->getBaseUrl() . '/' . $name . '/_mapping/' . $mapping;
        $data = $this->curl->sendRequest($url, 'PUT', $properties,$this->getHeaders());

        if(!isset($data['acknowledged'])){
            print_r($data);
            throw new \Exception('Cant create mapping "' . $mapping . '" for index "' . $name . '"');
        }
    }

    public function createPipeline(){
        $url = $this->getBaseUrl() . '_ingest/pipeline/attachment';
        $body = '{
         "description" : "Extract attachment information encoded in Base64 with UTF-8 charset",
         "processors" : [
           {
             "attachment" : {
               "field" : "data",
               "indexed_chars": -1
             }
           }
         ]
        }';
        $this->curl->sendRequest($url, 'PUT', $body, $this->getHeaders());
    }

    /**
     * @param IndexInterface $index
     * @throws \Exception
     */
    public function deleteIndex(IndexInterface $index){
        $name = $index->getIndexName();
        $url = $this->getBaseUrl() . '/' . $name;
        $this->curl->sendRequest($url, 'DELETE');
    }

    public function runQuery($url, $method = 'GET', $data = []){
        $url = $this->getBaseUrl() . '/' . $url;

        $results = $this->curl->sendRequest($url, $method, $data, $this->getHeaders());
        return $results;
    }

    /**
     * @return array
     */
    private function getHeaders(){
        return ['Content-Type: application/json'];
    }

    /**
     * @param $data
     * @param $method
     * @param $entityName
     * @throws \Exception
     */
    private function checkResponse($data, $method, $entityName){
        switch ($method) {
            case 'save':
                $allowedActions = ['updated', 'created'];
                break;
            case 'delete':
                $allowedActions = ['deleted', 'not_found'];
                break;
            default:
                throw new \Exception('Method not allowed');
        }

        if(!isset($data['result']) || !in_array($data['result'],$allowedActions)){
            print_r($data);
            throw new \Exception("Error. Cant $method $entityName");
        }
    }

    /**
     * @return string
     */
    private function getBaseUrl()
    {
        return $this->config['elastic']['base_url'];
    }
}