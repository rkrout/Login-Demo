<?php

require_once("db-utils.php");
require_once("date-utils.php");

$settings = find_one("SELECT * FROM settings LIMIT 1");

?>

<?php require("header.php") ?>

<a href="/timing/edit-settings.php" class="btn btn-primary">Edit Settings</a>

<table class="table mt-4">
    <thead>
        <tr>
            <th>Regular Time</th>
            <th>Break Time</th>
            <th>Break Interval</th>
            <th>Punch Type</th>
            <th>Double Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= get_sec_to_hour($settings["regular_time"]) ?>hr</td>
            <td><?= get_sec_to_minute($settings["break_time"]) ?>min</td>
            <td><?= get_sec_to_hour($settings["break_interval"]) ?>hr</td>
            <td><?= $settings["punch_type"] ?></td>
            <td><?= get_sec_to_hour($settings["double_time"]) ?>hr</td>
            <td>
                <a href="/timing/edit-settings.php" class="btn btn-sm btn-warning">Edit</a>
            </td>
        </tr>
    </tbody>
</table>

<?php require("footer.php") ?>