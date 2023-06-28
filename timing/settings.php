<?php

require_once("db-utils.php");
require_once("date-utils.php");

$settings = find_one("SELECT * FROM settings LIMIT 1");

function get_punch_type($punch_type)
{
    switch ($punch_type) 
    {
        case "split_punch":
            return "Split Punch";

        case "in_punch":
            return "In Punch";

        case "majority_hours":
            return "Majority Hours";
    }
}

?>

<?php require("header.php") ?>

<div class="overflow-auto">
    <table class="table mt-4" style="min-width: 1600px;">
        <thead>
            <tr>
                <th>Regular Time</th>
                <th>Break Time</th>
                <th>Break Interval</th>
                <th>Punch Type</th>
                <th>Double Time</th>
                <th>Consecutive Days</th>
                <th>Week Start Day</th>
                <th>In Time</th>
                <th>Out Time</th>
                <th>Launch Break</th>
                <th>Calculate Over Time</th>
                <th>Weekly Overtime</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= get_sec_to_hour($settings["regular_time"]) ?>hr</td>
                <td><?= get_sec_to_minute($settings["break_time"]) ?>min</td>
                <td><?= get_sec_to_hour($settings["break_interval"]) ?>hr</td>
                <td><?= get_punch_type($settings["punch_type"]) ?></td>
                <td><?= get_sec_to_hour($settings["double_time"]) ?>hr</td>
                <td><?= $settings["consecutive_days"] ?></td>
                <td><?= $settings["week_start_day"] ?></td>
                <td><?= date("h:i A", strtotime($settings["in_time"])) ?></td>
                <td><?= date("h:i A", strtotime($settings["out_time"])) ?></td>
                <td><?= date("h:i A", strtotime($settings["launch_break_start"])) . " - " . date("h:i A", strtotime($settings["launch_break_end"])) ?></td>
                <td><?= $settings["over_time_cal"] ?></td>
                <td><?= get_sec_to_hour($settings["weekly_over_time"]) ?>hr</td>
                <td>
                    <a href="/timing/edit-settings.php" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php require("footer.php") ?>