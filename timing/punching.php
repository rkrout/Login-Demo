<?php

require_once("db-utils.php");
require_once("date-utils.php");

$punch_in_time = $_POST["punch_in_time"];
$punch_out_time = $_POST["punch_out_time"];
$user_id = $_POST["user_id"];

if(is_greater($punch_in_time, $punch_out_time)) 
{
    die("<script>alert('Punch in date must be smaller than punch out date');window.location.href='/timing/index.php'</script>");
}

$setting = find_one("SELECT * FROM settings");

if($setting["punch_type"] == "in_punch")
{
    insert("timings", [
        "punch_in_time" => $punch_in_time,
        "punch_out_time" => $punch_out_time,
        "date" => $punch_in_time,
        "user_id" => $user_id
    ]);
}

else if($setting["punch_type"] == "split_punch")
{
    if(is_same_day($punch_in_time, $punch_out_time))
    {
        insert("timings", [
            "punch_in_time" => $punch_in_time,
            "punch_out_time" => $punch_out_time,
            "date" => $punch_in_time,
            "user_id" => $user_id
        ]);
    }
    else 
    {
        $split_times = get_split_times($punch_in_time, $punch_out_time);

        // echo "<pre>";
        // print_r($split_times);
        // echo "</pre>";
        // die;

        foreach ($split_times as $split_time) 
        {
            insert("timings", [
                "punch_in_time" => $split_time["punch_in_time"],
                "punch_out_time" => $split_time["punch_out_time"],
                "date" => $split_time["punch_in_time"],
                "user_id" => $user_id
            ]);
        }
    }
}

else 
{
    insert("timings", [
        "punch_in_time" => $punch_in_time,
        "punch_out_time" => $punch_out_time,
        "date" => get_majority_date($punch_in_time, $punch_out_time),
        "user_id" => $user_id
    ]);
}

require("edit-timings.php");

die("<script>alert('Data added successfully'); window.location.href='/timing/index.php'</script>");

?>