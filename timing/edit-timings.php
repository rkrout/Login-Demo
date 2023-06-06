<?php 

require("db.php");

$stmt = $pdo->prepare("SELECT * FROM timings");
$stmt->execute();
$timings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM settings LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

foreach ($timings as $timing) {
    $working_time_in_second = strtotime($timing["punch_out_time"]) - strtotime($timing["punch_in_time"]);

    $regular_time_in_second = $settings["regular_time"] * 3600;

    if($working_time_in_second > $regular_time_in_second) {
        $over_time_in_second = $working_time_in_second - $regular_time_in_second;

        $total_break = intdiv($working_time_in_second, $settings["break_interval"] * 3600);

        $break_time_in_second = 60 * $settings["break_time"];
    
        $total_break_time_in_second = $total_break * $break_time_in_second;

        $total_break_time = $total_break_time_in_second / 60;
    
        $over_time = ($over_time_in_second - $total_break_time_in_second) / 60;
    } else {
        $over_time = 0;

        $total_break_time = 0;
    }

    $stmt = $pdo->prepare("UPDATE timings SET overtime = :overtime, total_break_time = :total_break_time WHERE id = :id");
    $stmt->bindParam("overtime", $over_time);
    $stmt->bindValue("total_break_time", $total_break_time);
    $stmt->bindParam("id", $timing["id"]);
    $stmt->execute();
}
