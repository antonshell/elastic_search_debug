<?php

namespace src\index;

/**
 * Interface IndexInterface
 * @package src\index
 */
interface IndexInterface
{
    /**
     * @return string
     */
    public function getIndexName() : string;

    /**
     * @return string
     */
    public function getMappingName() : string;

    /**
     * @return array
     */
    public function getSettings() : array;

    /**
     * @return array
     */
    public function getProperties() : array;
}