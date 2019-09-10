<?php

namespace src\data;

/**
 * Class BaseDataProvider
 * @package src\data
 */
abstract class BaseDataProvider
{
    /**
     * @param $value
     * @return array
     */
    protected function getSuggest($value){
        $value = trim($value);
        $words = explode(' ', $value);

        $input = [];
        $count = count($words);
        for($i = 0; $i <$count; $i++){
            $tmpWords = array_slice($words, $i, $count);
            $tmpStr = implode(' ', $tmpWords);
            $input[] = $tmpStr;
        }

        return [
            "input" => $input
        ];
    }
}