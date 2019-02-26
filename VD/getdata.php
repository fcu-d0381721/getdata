<?php
require 'C:/composer/vendor/autoload.php';
// Configuration

// Connect
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
// var_dump($manager);

// Insert
// $bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
// $bulk->insert(['id' => 6, 'hello' => 'hi', 'name' => 'cindy']);
// $result = $manager->executeBulkWrite('OneYear.testCollection', $bulk);


// Query
$filter = [
    'hello' => "hi",
    'id' => 6,
    'name' => "cindy",
    // 'datacollecttime' => "2018-11-01 00:00:00",
    // // ['$regex' => '/.2018-11-36./']
    // 'speed' => ['$gt' => 60],
];
$options = [
    'projection' => ['_id' => 0],
];
$query = new MongoDB\Driver\Query($filter, $options);
$rows = $manager->executeQuery('OneYear.testCollection', $query); // $mongo contains the connection object to MongoDB
foreach($rows as $r){
   print_r($r);
}

?>