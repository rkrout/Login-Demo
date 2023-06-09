<?php 

function is_greater($first_date, $second_date)
{
    return strtotime($first_date) > strtotime($second_date);
}

function get_time_diff_in_sec($first_time, $second_time)
{
    return strtotime($first_time) - strtotime($second_time);
}

function is_same_day($first_date, $second_date)
{
    return date("Y-m-d", strtotime($first_date)) == date("Y-m-d", strtotime($second_date));
}

function get_dates_between($from_date, $to_date)
{
    $date_between = [];

    $i_date_from = mktime(1, 0, 0, substr($from_date, 5, 2), substr($from_date, 8, 2), substr($from_date, 0, 4));
    $i_date_to = mktime(1, 0, 0, substr($to_date, 5, 2), substr($to_date, 8, 2), substr($to_date, 0, 4));

    if ($i_date_to >= $i_date_from) 
    {
        array_push($date_between, date("Y-m-d", $i_date_from)); 

        while ($i_date_from<$i_date_to) 
        {
            $i_date_from += 86400; 
            array_push($date_between, date("Y-m-d", $i_date_from));
        }
    }

    return $date_between;
}

function get_split_times($start_date, $end_date)
{
    $between_dates = get_dates_between($start_date, $end_date);

    $split_times = [];

    for($i = 0; $i < count($between_dates); $i++) 
    {
        if($i == 0) 
        {
            $punch_in_time = $between_dates[$i] . " " . date("H:i:s", strtotime($start_date));

            $punch_out_time = $between_dates[$i] . " 23:59:59";
        }
        
        else if($i == count($between_dates) - 1)
        {
            $punch_in_time = $between_dates[$i] . " 00:00:00";

            $punch_out_time = $between_dates[$i] . " " . date("H:i:s", strtotime($end_date));
        } 
        
        else 
        {
            $punch_in_time = $between_dates[$i] . " 00:00:00";

            $punch_out_time = $between_dates[$i] . " 23:59:59";
        } 

        array_push($split_times, [
            "punch_in_time" => $punch_in_time,
            "punch_out_time" => $punch_out_time,
            "working_time" => get_time_diff_in_sec($punch_out_time, $punch_in_time)
        ]);
    }

    return $split_times;
}

function get_majority_date($start_time, $end_time)
{
    $split_times = get_split_times($start_time, $end_time);

    $largest_working_time = $split_times[0]["working_time"];
    
    $majority_date = date("Y-m-d", strtotime($split_times[0]["punch_in_time"]));

    foreach ($split_times as $split_time) 
    {
        if($split_time["working_time"] > $largest_working_time) 
        {
            $largest_working_time = $split_time["working_time"];
            $majority_date = date("Y-m-d", strtotime($split_time["punch_in_time"]));
        }
    }

    return $majority_date;
}

function get_sec_to_time($second, $include_second = true)
{
    $hour = intdiv($second, 3600);

    $remaining_second = $second % 3600;

    $minute = intdiv($remaining_second, 60);

    $second = $remaining_second % 60;

    $time = "$hour:$minute";

    if($include_second) $time .= ":$second";

    return $time;
}

function get_sec_to_hour($second)
{
    return intdiv($second, 3600);
}

function get_sec_to_minute($second)
{
    return intdiv($second, 60);
}

function get_day_from_date($date)
{
    return date("D", strtotime($date));
}

// print_r(get_majority_date("2023-06-9 10:00:00", "2023-06-10 01:00:00"));