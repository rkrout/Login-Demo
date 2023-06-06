<?php 

require("db.php");

$stmt = $pdo->prepare("SELECT * FROM timings");
$stmt->execute();
$timings = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($timings as $timing) {
    $working_time_in_second = strtotime($timing["punch_out_time"]) - strtotime($timing["punch_in_time"]);

    $regular_time_in_second = $timing["regular_time"] * 3600;

    if($working_time_in_second > $regular_time_in_second) {
        $over_time_in_second = $working_time_in_second - $regular_time_in_second;

        $total_break = intdiv($working_time_in_second, $timing["break_interval"] * 3600);

        $break_time_in_second = 60 * $timing["break_time"];
    
        $total_break_time_in_second = $total_break * $break_time_in_second;
    
        $over_time_in_second = $over_time_in_second - $total_break_time_in_second;
    } else {
        $over_time_in_second = 0;
    }

    $stmt = $pdo->prepare("update timings set overtime = :overtime, total_break_time = :total_break_time where id = :id");
    $stmt->bindParam("overtime", $over_time_in_second);
    $stmt->bindValue("total_break_time", $total_break_time_in_second / 60);
    $stmt->bindParam("id", $timing["id"]);
    $stmt->execute();
}
