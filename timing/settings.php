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

<div class="max-w-xl mx-auto">
    <a href="/timing/edit-settings.php" class="btn btn-primary">Edit Settings</a>

    <table class="table mt-4 text-left">
        <tr>
            <td>Regular Time</td>
            <td><?= get_sec_to_hour($settings["regular_time"]) ?>hr</td>
        </tr>
        <tr>
            <td>Break Time</td>
            <td><?= get_sec_to_minute($settings["break_time"]) ?>min</td>
        </tr>
        <tr>
            <td>Break Interval</td>
            <td><?= get_sec_to_hour($settings["break_interval"]) ?>hr</td>
        </tr>
        <tr>
            <td>Punch Type</td>
            <td><?= get_punch_type($settings["punch_type"]) ?></td>
        </tr>
        <tr>
            <td>Double Time</td>
            <td><?= get_sec_to_hour($settings["double_time"]) ?>hr</td>
        </tr>
        <tr>
            <td>Consecutive Days</td>
            <td><?= $settings["consecutive_days"] ?></td>
        </tr>
        <tr>
            <td>Week Start Day</td>
            <td><?= $settings["week_start_day"] ?></td>
        </tr>
        <tr>
            <td>In Time</td>
            <td><?= date("h:i A", strtotime($settings["in_time"])) ?></td>
        </tr>
        <tr>
            <td>Out Time</td>
            <td><?= date("h:i A", strtotime($settings["out_time"])) ?></td>
        </tr>
        <tr>
            <td>Launch Break</td>
            <td><?= date("h:i A", strtotime($settings["launch_break_start"])) . " - " . date("h:i A", strtotime($settings["launch_break_end"])) ?></td>
        </tr>
        <tr>
            <td>Calculate Over Time</td>
            <td><?= $settings["over_time_cal"] ?></td>
        </tr>
        <tr>
            <td>Weekly Over Time</td>
            <td><?= get_sec_to_hour($settings["weekly_over_time"]) ?>hr</td>
        </tr>
        <tr>
            <td>Calculate Double Time</td>
            <td><?= $settings["double_time_cal"] ?></td>
        </tr>
        <tr>
            <td>Weekly Double Time</td>
            <td><?= get_sec_to_hour($settings["weekly_double_time"]) ?>hr</td>
        </tr>
    </table>
</div>

<?php require("footer.php") ?>