<?php 

require_once("db-utils.php");

$schedules = find_all("
    SELECT 
        schedules.id,
        schedules.name,
        user_schedules.from_date,
        user_schedules.to_date,
        users.id AS user_id, 
        users.name AS user_name
    FROM users 
    LEFT JOIN user_schedules ON user_schedules.user_id = users.id
    LEFT JOIN schedules ON schedules.id = user_schedules.schedule_id
");


for($i = 0; $i < count($schedules); $i++)
{
    $schedules[$i]["days"] = find_all("SELECT * FROM schedule_days WHERE schedule_id = :schedule_id", [
        "schedule_id" => empty($schedules[$i]["id"]) ? -1 : $schedules[$i]["id"]
    ]);
}

?>

<?php require("header.php") ?>

<div class="table-responsive">
    <table class="table min-w-[1024px]">
        <thead>
            <tr>
                <th>Name</th>
                <th>Schedule</th>
                <th>Time Period</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($schedules as $schedule): ?>
                <tr>
                    <td><?= $schedule["user_name"] ?></td>

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

                    <td><?= $schedule["from_date"] && $schedule["to_date"] ? (date("d-m-Y", strtotime($schedule["from_date"])) . " - " . date("d-m-Y", strtotime($schedule["to_date"]))) : "NA" ?></td>

                    <td>
                        <a href="/multiple-punch/edit-user-schedule.php?user_id=<?= $schedule["user_id"] ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require("footer.php") ?>