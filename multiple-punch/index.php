<?php 

require_once("db-utils.php");
require_once("date-utils.php");

function set_break_time(&$working_days, $settings) 
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

            $working_days[$i] = array_merge($working_days[$i], [
                "office_time" => $office_time,
                "break_time" => $break_time,
                "working_time" => $working_time,
                "over_time" => $over_time
            ]);
        }
    }
}

$working_days = find_all("SELECT working_days.*, users.name AS user_name FROM working_days INNER JOIN users ON users.id = working_days.user_id");

$settings = find_one("SELECT * FROM settings LIMIT 1");

for($i = 0; $i < count($working_days); $i++)
{
    $working_days[$i]["punches"] = find_all("SELECT punch_in_time, punch_out_time FROM punches WHERE working_day_id = " . $working_days[$i]["id"]);
}

set_break_time($working_days, $settings);

echo "<pre>";
print_r($working_days);
echo "</pre>";

?>

<?php require("header.php")  ?>

<div class="table-responsive">
    <table class="table min-w-[1024px]">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Punches</th>
                <th>Break Time</th>
                <th>Over Time</th>
                <th>Working Time</th>
                <th>Office Time</th>
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

                        <td><?= date("d-m-Y", strtotime($working_day["start_time"])); ?></td>

                        <td>
                            <?php foreach($working_day["punches"] as $punches): ?>
                                <p><?= date("h:i A", strtotime($punches["punch_in_time"])) . " - " . ($punches["punch_out_time"] ? date("h:i A", strtotime($punches["punch_out_time"])) : "NA") ?></p>
                            <?php endforeach; ?> 
                        </td>

                        <td><?= isset($working_day["break_time"]) ? get_sec_to_time($working_day["break_time"]) : "NA" ?></td>

                        <td><?= isset($working_day["over_time"]) ?  get_sec_to_time($working_day["over_time"]) : "NA" ?></td>

                        <td><?= isset($working_day["working_time"]) ? get_sec_to_time($working_day["working_time"]) : "NA" ?></td>

                        <td><?= isset($working_day["office_time"]) ? get_sec_to_time($working_day["office_time"]) : "NA" ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require("footer.php") ?>