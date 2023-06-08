<?php 

require_once("db.php");

function insert($table, $data)
{
    global $pdo;

    $sql = "INSERT INTO $table (";

    $index = 0;
    foreach ($data as $key => $value) 
    {
        $sql .= $key;

        if($index != count($data) - 1)
        {
            $sql .= ",";
        }
        else 
        {
            $sql .= ")";
        }

        $index++;
    }

    $sql .= " VALUES (";

    $index = 0;
    foreach ($data as $key => $value) 
    {
        $sql .= ":$key";

        if($index != count($data) - 1)
        {
            $sql .= ",";
        }
        else 
        {
            $sql .= ")";
        }

        $index++;
    }

    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $value) 
    {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
}

function query($sql, $data = [])
{
    global $pdo;

    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $value) 
    {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
}

function find_one($sql, $data = [])
{
    global $pdo;

    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $value) 
    {
        $stmt->bindParam($key, $value);
    }

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function find_all($sql, $data = [])
{
    global $pdo;

    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $value) 
    {
        $stmt->bindParam($key, $value);
    }

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}