<?php

namespace src\data;

/**
 * Class PostDataProvider
 * @package src\data
 */
interface DataProviderInterface
{
    public function getData() :array;
}