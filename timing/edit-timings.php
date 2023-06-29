<?php 
require_once("db-utils.php");
require_once("date-utils.php");

$timings = find_all("SELECT * FROM timings");

$settings = find_one("SELECT * FROM settings LIMIT 1");

foreach ($timings as $timing) 
{
    $office_time = get_time_diff_in_sec($timing["punch_out_time"], $timing["punch_in_time"]);

    $break_time = intdiv($office_time, $settings["break_interval"]) * $settings["break_time"];

    $working_time = $office_time - $break_time;

    $over_time = $working_time > $settings["regular_time"] ? $working_time - $settings["regular_time"] : 0;

    // calculate double time - if employee works on out of consecutive day then double time = work time

    $range = get_week_range($timing["punch_in_time"]);

    if(strtotime($range["work_end_date"]) < strtotime(date("Y-m-d", strtotime($timing["punch_in_time"]))))
    {
        $double_time = $working_time;
    }
    else 
    {
        $double_time = $working_time > $settings["double_time"] ? $working_time - $settings["double_time"] : 0;
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
        "working_time" => $working_time,
        "over_time" => $over_time,
        "break_time" => $break_time,
        "double_time" => $double_time,
        "id" => $timing["id"]
    ]);
}

