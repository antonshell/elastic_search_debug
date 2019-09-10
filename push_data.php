<?php

use src\data\ProductDataProvider;
use src\ElasticClient;
use src\index\ProductIndex;

require_once '_bootstrap.php';
require_once '_config.php';

$client = new ElasticClient();

// products
echo "### Push products ###\n";
$postDataProvider = new ProductDataProvider();
$data = $postDataProvider->getData();
$index = new ProductIndex();

$client->deleteIndex($index);
$client->createIndex($index);
$client->createMapping($index);

foreach ($data as $row){
    $result = $client->saveItem($index, $row);

    $action = $result['result'];
    $id = $result['_id'];

    $message = $action . " item #" . $row['id'] . "\n";
    echo $message;
}