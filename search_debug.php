<?php

use src\Config;
use src\ProductSearch;

require '_bootstrap.php';

$productSearch = new ProductSearch();

$config = Config::load();
$queries = $config['queries'];

foreach ($queries as $query){
    try {
        $results = $productSearch->search($query);
        $results = $productSearch->processResults($results);

        echo "Query: $query\n";

        foreach ($results as $row){
            $message = $row['name'];
            echo "\t$message\n";
        }
    } catch (Exception $e) {
        echo $e->getMessage() . "\n";
    }
}