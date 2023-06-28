<?php 

require_once("db-utils.php");
require_once("date-utils.php");
require("header.php");

$working_days = find_all("SELECT working_days.*, users.name AS user_name FROM working_days INNER JOIN users ON users.id = working_days.user_id");

for($i = 0; $i < count($working_days); $i++)
{
    $working_days[$i]["punches"] = find_all("SELECT punch_in_time, punch_out_time FROM punches WHERE working_day_id = " . $working_days[$i]["id"]);
}

?>

<?php
// function get_dates($date)
// {
//     $settings = find_one("SELECT * FROM settings");
//     $result = [];
//     if(strtotime($settings["in_time"]) >= strtotime($settings["out_time"]))
//     {
//         $start_time = $date . " " . $settings["in_time"];

//         $end_time = date("Y-m-d", strtotime("+1 days", strtotime($date))) . " " . $settings["out_time"];

//         array_push($result, [
//             $start_time,
//             $end_time
//         ]);

//         $next_day = date("Y-m-d", strtotime("+1 days", strtotime($date)));

//         $start_time = $next_day . " " . $settings["in_time"];

//         $end_time = date("Y-m-d", strtotime("+1 days", strtotime($next_day))) . " " . $settings["out_time"];

//         array_push($result, [
//             $start_time,
//             $end_time
//         ]);

//         $previous_day = date("Y-m-d", strtotime("-1 days", strtotime($date)));

//         $start_time = $previous_day . " " . $settings["in_time"];

//         $end_time = date("Y-m-d", strtotime("+1 days", strtotime($previous_day))) . " " . $settings["out_time"];

//         array_push($result, [
//             $start_time,
//             $end_time
//         ]);
//     }
//     else 
//     {
//         $start_time = $date . " " . $settings["in_time"];

//         $end_time = $date . " " . $settings["out_time"];

//         array_push($result, [
//             $start_time,
//             $end_time
//         ]);

//         $next_day = date("Y-m-d", strtotime("+1 days", strtotime($date)));

//         $start_time = $next_day . " " . $settings["in_time"];

//         $end_time = $next_day . " " . $settings["out_time"];

//         array_push($result, [
//             $start_time,
//             $end_time
//         ]);

//         $previous_day = date("Y-m-d", strtotime("-1 days", strtotime($date)));

//         $start_time = $previous_day . " " . $settings["in_time"];

//         $end_time = $previous_day . " " . $settings["out_time"];

//         array_push($result, [
//             $start_time,
//             $end_time
//         ]);
//     }

//     return $result;
// }

// echo "<pre>";
// print_r(get_dates("2023-06-28"));
// echo "</pre>";
?>


<div class="table-responsive">
    <table class="table min-w-[1024px]">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Punches</th>
                <th>Break Time</th>
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

                        <td>
                            <?php
                                $start_time = date("d-m-Y", strtotime($working_day["start_time"]));
                                $end_time = date("d-m-Y", strtotime($working_day["end_time"]));
                            ?>
                            <?php if($start_time == $end_time): ?>
                                <?= $start_time ?>
                            <?php else: ?>
                                <?= $start_time . " - " . $end_time ?>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <?php foreach($working_day["punches"] as $punches): ?>
                                <p><?= date("h:i A", strtotime($punches["punch_in_time"])) . " - " . ($punches["punch_out_time"] ? date("h:i A", strtotime($punches["punch_out_time"])) : "NA") ?></p>
                            <?php endforeach; ?> 
                        </td>

                        <td><?= get_sec_to_time($working_day["break_time"]) ?></td>

                        <td><?= get_sec_to_time($working_day["working_time"]) ?></td>

                        <td><?= get_sec_to_time($working_day["office_time"]) ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require("footer.php") ?>