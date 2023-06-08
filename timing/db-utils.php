<?php 

function insert($table, $data)
{
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

    die($sql);

    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $value) 
    {
        $stmt->bindParam($key, $value);
    }

    $stmt->execute();
}

function query($sql, $data)
{
    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $value) 
    {
        $stmt->bindParam($key, $value);
    }

    $stmt->execute();
}

function find_one($sql, $data)
{
    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $value) 
    {
        $stmt->bindParam($key, $value);
    }

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result[0];
}

function find_all($sql, $data)
{
    $stmt = $pdo->prepare($sql);

    foreach ($data as $key => $value) 
    {
        $stmt->bindParam($key, $value);
    }

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}