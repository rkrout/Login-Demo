<?php 
require_once("../utils/db-utils.php");
require_once("../utils/helper-utils.php");

$config = json_decode(file_get_contents("db-config.json"), true);

if(isset($config["host"]) && isset($config["name"]) && isset($config["username"]) && isset($config["password"]))
{
    $pdo = new PDO("mysql:host=". $config["host"] .";dbname=" . $config["name"], $config["username"], $config["password"]);
}
else 
{
    redirect("/db-setup/create-db-setup.php");
}