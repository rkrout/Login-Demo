<?php

require_once("db-utils.php");

$schedules = find_all("SELECT * FROM schedules");

for($i = 0; $i < count($schedules); $i++)
{
    $schedules[$i]["days"] = find_all("SELECT * FROM schedule_days WHERE schedule_id = :id", ["id" => $schedules[$i]["id"]]);
}

?>

<?php require("header.php") ?>

<a href="/multiple-punch/create-schedule.php" class="btn btn-primary">Create New</a>

<table class="table mt-4">
    <thead>
        <tr>
            <th>Name</th>
            <th>Days</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($schedules as $schedule): ?>
            <tr>
                <td><?= $schedule["name"] ?></td>

                <td>
                    <table class="table text-sm">
                        <thead>
                            <tr>
                                <td>Day</td>
                                <td>In Time</td>
                                <td>Out Time</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($schedule["days"] as $day): ?>
                                <tr>
                                    <td><?= ucfirst($day["day"]) ?></td>
                                    <td><?= $day["in_time"] ? date("h:i A", strtotime($day["in_time"])) : "NA" ?></td>
                                    <td><?= $day["out_time"] ? date("h:i A", strtotime($day["out_time"])) : "NA" ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </td>

                <td>
                    <a href="/multiple-punch/edit-schedule.php?id=<?= $schedule["id"] ?>" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require("footer.php") ?>