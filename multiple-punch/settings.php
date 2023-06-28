<?php

require_once("db-utils.php");

$settings = find_one("SELECT * FROM settings LIMIT 1");

?>

<?php require("header.php") ?>

<table class="table mt-4">
    <thead>
        <tr>
            <th>In Time</th>
            <th>Out Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= date("h:i A", strtotime($settings["in_time"])) ?></td>
            <td><?= date("h:i A", strtotime($settings["out_time"])) ?></td>
            <td>
                <a href="/multiple-punch/edit-settings.php" class="btn btn-sm btn-warning">Edit</a>
            </td>
        </tr>
    </tbody>
</table>

<?php require("footer.php") ?>