<?php 

function get_time_difference_in_sec($first_time, $second_time)
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

function get_split_timings($start_date, $end_date, $between_dates)
{
    $split_timings = [];

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

        array_push($split_timings, [
            "punch_in_time" => $punch_in_time,
            "punch_out_time" => $punch_out_time,
            "working_time" => get_time_difference_in_sec($punch_out_time, $punch_in_time)
        ]);
    }

    return $split_timings;
}

function get_majority_date($split_times)
{
    $largest_working_time = $split_times[0]["working_time"];
    $majority_date = date("Y-m-d", strtotime($split_times[0]["punch_in_time"]));

    foreach ($split_times as $split_time) 
    {
        if($split_time["working_time"] > $majority_date) 
        {
            $largest_working_time = $split_time["working_time"];
            $majority_date = date("Y-m-d", strtotime($split_time["punch_in_time"]));
        }
    }

    return $majority_date;
}

print_r(get_majority_date(get_split_timings("2022-03-02 10:00:00", "2022-03-03 22:00:00", get_dates_between("2022-03-02", "2022-03-03"))));