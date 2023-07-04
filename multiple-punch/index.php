<?php 

require_once("db-utils.php");
require_once("date-utils.php");

function set_punch_type(&$working_days, $settings) 
{
    $result = [];

    for ($i = 0; $i < count($working_days); $i++)
    {
        $total_punches = count($working_days[$i]["punches"]);

        if(empty($working_days[$i]["punches"][$total_punches - 1]["punch_out_time"]))
        {
            $working_days[$i]["date"] = $working_days[$i]["punches"][0]["punch_in_time"];

            array_push($result, $working_days[$i]);

            continue;
        }

        if($settings["punch_type"] == "Split Punch")
        {
            $punch_in_time = $working_days[$i]["punches"][0]["punch_in_time"];
            
            $punch_out_time = $working_days[$i]["punches"][$total_punches - 1]["punch_out_time"];

            if(date("d", strtotime($punch_in_time)) != date("d", strtotime($punch_out_time)))
            {
                $days = get_split_times($punch_in_time, $punch_out_time);

                foreach ($days as $day) 
                {
                    $data = [
                        "id" => $working_days[$i]["id"],
                        "start_time" => $day["punch_in_time"],
                        "end_time" => $day["punch_out_time"],
                        "date" => $day["punch_in_time"],
                        "user_id" => $working_days[$i]["user_id"],
                        "user_name" => $working_days[$i]["user_name"],
                        "punches" => [],
                    ];

                    foreach ($working_days[$i]["punches"] as $punch) 
                    {
                        if(strtotime($punch["punch_in_time"]) >= strtotime($day["punch_in_time"]) && strtotime($punch["punch_in_time"]) <= strtotime($day["punch_out_time"]))
                        {
                            array_push($data["punches"], $punch);
                        }
                    }

                    array_push($result, $data);
                }
            }
            else 
            {
                $working_days[$i]["date"] = $working_days[$i]["punches"][0]["punch_in_time"];
                array_push($result, $working_days[$i]);
            }
        }

        else if($settings["punch_type"] == "Majority Hours")
        {
            $punch_in_time = $working_days[$i]["punches"][0]["punch_in_time"];
            
            $punch_out_time = $working_days[$i]["punches"][$total_punches - 1]["punch_out_time"];

            $working_days[$i]["date"] = get_majority_date($punch_in_time, $punch_out_time);

            array_push($result, $working_days[$i]);
        }

        else 
        {
            $working_days[$i]["date"] = $working_days[$i]["punches"][0]["punch_in_time"];

            array_push($result, $working_days[$i]);
        }
    }

    $working_days = $result;
}

function set_timings(&$working_days, $settings) 
{
    for ($i = 0; $i < count($working_days); $i++) 
    {
        $total_punches = count($working_days[$i]["punches"]);

        if($total_punches > 0 && $working_days[$i]["punches"][$total_punches - 1]["punch_out_time"])
        {
            $office_time = get_time_diff_in_sec($working_days[$i]["punches"][$total_punches - 1]["punch_out_time"], $working_days[$i]["punches"][0]["punch_in_time"]);

            $break_time = intdiv($office_time, $settings["break_interval"]) * $settings["break_time"];
    
            $working_time = $office_time - $break_time;
    
            $over_time = $working_time > $settings["regular_time"] ? $working_time - $settings["regular_time"] : 0;

            $punch_in_time = $working_days[$i]["punches"][0]["punch_in_time"];

            $range = get_week_range($punch_in_time, $settings["week_start_day"], $settings["consecutive_days"]);

            if(strtotime($range["work_end_date"]) < strtotime(date("Y-m-d", strtotime($punch_in_time))))
            {
                $double_time = $working_time;
            }
            else 
            {
                $double_time = $working_time > $settings["double_time"] ? $working_time - $settings["double_time"] : 0;
            }

            $working_days[$i] = array_merge($working_days[$i], [
                "office_time" => $office_time,
                "break_time" => $break_time,
                "working_time" => $working_time,
                "over_time" => $over_time,
                "double_time" => $double_time
            ]);
        }
    }
}

function set_settings(&$settings) 
{
    $settings = find_one("SELECT * FROM settings LIMIT 1");    
}

function set_working_days(&$working_days, $settings) 
{
    $working_days = find_all("SELECT working_days.*, users.name AS user_name FROM working_days INNER JOIN users ON users.id = working_days.user_id");
    
    for($i = 0; $i < count($working_days); $i++)
    {
        $working_days[$i]["punches"] = find_all("SELECT punch_in_time, punch_out_time FROM punches WHERE working_day_id = " . $working_days[$i]["id"]);
    }    
}

set_settings($settings);

set_working_days($working_days, $settings);

set_punch_type($working_days, $settings);

set_timings($working_days, $settings);

?>

<?php require("header.php")  ?>

<div class="table-responsive">
    <table class="table min-w-[1024px]">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Day</th>
                <th>Punches</th>
                <th>Break Time</th>
                <th>Over Time</th>
                <th>Working Time</th>
                <th>Office Time</th>
                <th>Double Time</th>
            </tr>
        </thead>
        
        <tbody>
            <?php if(count($working_days) == 0): ?>
                <tr>
                    <td colspan="6">No Data Found</td>
                </tr>
            <?php endif; ?>

            <?php foreach($working_days as $working_day): ?>
                <?php if(count($working_day["punches"]) > 0): ?>
                    <tr>
                        <td><?= $working_day["user_name"] ?></td>

                        <td><?= date("d-m-Y", strtotime($working_day["date"])); ?></td>

                        <td><?= date("l", strtotime($working_day["date"])); ?></td>

                        <td>
                            <?php foreach($working_day["punches"] as $punches): ?>
                                <p><?= date("h:i A", strtotime($punches["punch_in_time"])) . " - " . ($punches["punch_out_time"] ? date("h:i A", strtotime($punches["punch_out_time"])) : "NA") ?></p>
                            <?php endforeach; ?> 
                        </td>

                        <td><?= isset($working_day["break_time"]) ? get_sec_to_time($working_day["break_time"]) : "NA" ?></td>

                        <td><?= isset($working_day["over_time"]) ?  get_sec_to_time($working_day["over_time"]) : "NA" ?></td>

                        <td><?= isset($working_day["working_time"]) ? get_sec_to_time($working_day["working_time"]) : "NA" ?></td>

                        <td><?= isset($working_day["office_time"]) ? get_sec_to_time($working_day["office_time"]) : "NA" ?></td>
                        
                        <td><?= isset($working_day["double_time"]) ? get_sec_to_time($working_day["double_time"]) : "NA" ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require("footer.php") ?>