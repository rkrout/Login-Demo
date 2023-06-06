<?php 

$dbConfig = json_decode(file_get_contents("db-config.json"));

if(isset($dbConfig->host) && isset($dbConfig->name) && isset($dbConfig->username) && isset($dbConfig->password)) {
    $pdo = new PDO("mysql:host={$dbConfig->host};dbname={$dbConfig->name}", $dbConfig->username, $dbConfig->password);
} else {
    require("create-db-setup.php");
    die();
}
