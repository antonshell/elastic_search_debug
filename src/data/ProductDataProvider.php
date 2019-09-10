<?php

namespace src\data;

/**
 * Class ProductDataProvider
 * @package src\data
 */
class ProductDataProvider extends BaseDataProvider implements DataProviderInterface
{
    /**
     * @return array
     */
    public function getData():array
    {
        $rows = file_get_contents(__DIR__ . '/../../data/products.json');
        $rows = json_decode($rows, true);

        $data = [];
        foreach ($rows as $row){
            $row['name'] = $row['vendor'] . ' ' . $row['model'] . ' ' . $row['year'];
            $data[] = $row;
        }

        return $data;
    }
}