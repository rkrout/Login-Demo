<?php 

require_once("db-utils.php");

$settings = find_one("SELECT * FROM settings");

$todays_date = strtotime(date("Y-m-d"));

for($i = 0; $i < 7; $i++)
{
    $start_time = date("Y-m-d", strtotime("+$i days", $todays_date)) . " " . $settings["in_time"];

    if(strtotime($settings["in_time"]) > strtotime($settings["out_time"]))
    {
        $end_time = date("Y-m-d", strtotime("+". $i + 1 ." days", $todays_date)) . " " . $settings["out_time"];
    }
    else 
    {
        $end_time = date("Y-m-d", strtotime("+$i days", $todays_date)) . " " . $settings["out_time"];
    }

    query("INSERT INTO working_days (start_time, end_time, user_id) VALUES ('$start_time', '$end_time', 1)");
}

?>