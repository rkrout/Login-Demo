<?php 

require_once("db-utils.php");

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

function get_week_range($date) 
{
    $date = strtotime($date);

    $week = [];

    $settings = find_one("SELECT * FROM settings LIMIT 1");
    
    if(date("l", $date) == $settings["week_start_day"])
    {
        $week["start_date"] = date("Y-m-d", $date);
    }
    else 
    {
        $week["start_date"] = date("Y-m-d", strtotime("last " . strtolower($settings["week_start_day"]), $date));
    }
    
    $week["end_date"] = date("Y-m-d", strtotime("+6 days", strtotime($week["start_date"])));
    
    $week["work_end_date"] = date("Y-m-d", strtotime("+". $settings["consecutive_days"] - 1 ." days", strtotime($week["start_date"])));
    
    return $week;
}

function get_pre_week_ranges($num)
{
    $ranges = [];
    
    for($i = 0; $i < $num; $i++)
    {
        array_push($ranges, get_week_range(date("Y-m-d", strtotime("-$i week +1 day"))));
    }
    
    return $ranges;
}

function get_week_date_range($date)
{
    $day = date("d", strtotime($date));
    
    if($day >= 22)
    {
        return [
            "22-" . date("m", strtotime($date)) . "-" . date("Y", strtotime($date)), 
            
            date("t-m-Y", strtotime($date))
        ];
    }
    else if($day >= 15)
    {
        return [
            "15-" . date("m", strtotime($date)) . "-" . date("Y", strtotime($date)), 
            
            "21-" . date("m", strtotime($date)) . "-" . date("Y", strtotime($date)),
        ];
    }
    else if($day >= 8)
    {
        return [
            "8-" . date("m", strtotime($date)) . "-" . date("Y", strtotime($date)), 
            
            "15-" . date("m", strtotime($date)) . "-" . date("Y", strtotime($date)),
        ];
    }
    else if($day >= 1)
    {
        return [
            "1-" . date("m", strtotime($date)) . "-" . date("Y", strtotime($date)), 
            
            "7-" . date("m", strtotime($date)) . "-" . date("Y", strtotime($date)),
        ];
    }
}

function get_weekly_sorted($dates) 
{
    $result = [];

    for($i = 0; $i < count($dates); $i++)
    {
        if($dates[$i] == null) continue;
        
        $week_range = get_week_range($dates[$i]["date"]);
        
        array_push($result, []);
        
        for($j = 0; $j < count($dates); $j++)
        {
            if($dates[$j] == null) continue;
            
            if(strtotime($dates[$j]["date"]) >= strtotime($week_range["start_date"]) && strtotime($dates[$j]["date"]) <= strtotime($week_range["end_date"]))
            {
                array_push($result[count($result) - 1], $dates[$j]); 

                $dates[$j] = null;
            }
        }
    }
    
    return $result;
}