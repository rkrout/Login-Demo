<?php 

require("db.php");
$stmt = $pdo->prepare("select * from timings");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $working_time_in_second = strtotime($user["punch_out_time"]) - strtotime($user["punch_in_time"]);
    $regular_time_in_second = $users[0]["regular_time"] * 3600;
    if($working_time_in_second > $regular_time_in_second) {
        $over_time_in_second = $working_time_in_second - $regular_time_in_second;
    } else {
        $over_time_in_second = 0;
    }
    $stmt = $pdo->prepare("update timings set overtime = :overtime where id = :id");
    $stmt->bindParam("overtime", $over_time_in_second);
    $stmt->bindParam("id", $user["id"]);
    $stmt->execute();
}
