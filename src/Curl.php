<?php

namespace src;

/**
 * Class Curl
 * @package DIY\ElasticSuite\Helper
 */
class Curl
{
    /**
     * @param $url
     * @param string $method
     * @param string $data
     * @param array $headers
     * @return mixed
     */
    public function sendRequest($url, $method = 'GET', $data = '', array $headers = []){
        if(is_array($data)){
            $data = json_encode($data);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if(count($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $result = curl_exec($ch);

        $result = json_decode($result,true);

        return $result;
    }
}