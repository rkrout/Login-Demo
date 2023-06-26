<?php 
require_once("db-utils.php");
require_once("date-utils.php");

$timings = find_all("SELECT * FROM timings");

$settings = find_one("SELECT * FROM settings LIMIT 1");

foreach ($timings as $timing) 
{
    $office_time_in_sec = get_time_diff_in_sec($timing["punch_out_time"], $timing["punch_in_time"]);

    $break_time_in_sec = intdiv($office_time_in_sec, $settings["break_interval"]) * $settings["break_time"];

    $working_time_in_sec = $office_time_in_sec - $break_time_in_sec;

    $over_time_in_sec = $working_time_in_sec > $settings["regular_time"] ? $working_time_in_sec - $settings["regular_time"] : 0;

    $range = get_week_range($timing["punch_in_time"]);

    if(strtotime($range["work_end_date"]) < strtotime($timing["punch_in_time"]))
    {
        $double_time_in_sec = $working_time_in_sec;
    }
    else 
    {
        $double_time_in_sec = $working_time_in_sec > $settings["double_time"] ? $working_time_in_sec - $settings["double_time"] : 0;
    }

    $sql = "
        UPDATE timings 
        SET 
            working_time = :working_time,
            over_time = :over_time, 
            break_time = :break_time, 
            double_time = :double_time 
        WHERE id = :id
    ";

    query($sql, [
        "working_time" => $working_time_in_sec,
        "over_time" => $over_time_in_sec,
        "break_time" => $break_time_in_sec,
        "double_time" => $double_time_in_sec,
        "id" => $timing["id"]
    ]);
}
