<?php
// Configuration
$dbhost = 'localhost';
$dbname = 'OneYear';
$collection = 'testCollection';
 
// Connect to mongo database
$mongoClient = new \MongoClient('mongodb://' . $dbhost);
$db = $mongoClient->$dbname;
 
// Get the users collection
$cUsers = $db->$collection;
 
// Insert object
$user = array(
    'first_name' => 'SJ',
    'last_name' => 'Mongo',
    'roles' => array('developer','bugmaker')
);
 
$user = array(
    'name'=> '2018 ithome鐵人賽'
);
$user = $cUsers->findOne($user);
 
// output
print_r($user);

?>