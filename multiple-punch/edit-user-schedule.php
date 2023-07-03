<?php

require_once("db-utils.php");

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $schedule = find_one("SELECT 1 FROM user_schedules WHERE user_id = :user_id LIMIT 1", ["user_id" => $_POST["user_id"]]);

    if($schedule)
    {
        $sql = "
            UPDATE user_schedules 
            SET 
                schedule_id = :schedule_id, 
                from_date = :from_date,
                to_date = :to_date
            WHERE user_id = :user_id
        ";

        query($sql, [
            "schedule_id" => $_POST["schedule_id"],
            "user_id" => $_POST["user_id"],
            "from_date" => [
                $_POST["from_date"],
                PDO::PARAM_STR
            ],
            "to_date" => [
                $_POST["to_date"],
                PDO::PARAM_STR
            ],
        ]);
    }
    else 
    {
        insert("user_schedules", [
            "schedule_id" => $_POST["schedule_id"],
            "user_id" => $_POST["user_id"],
            "from_date" => $_POST["from_date"],
            "to_date" => $_POST["to_date"]
        ]);
    }
    
    die("<script>window.location.href='/multiple-punch/user-schedules.php'</script>");
}

$user = find_one("
    SELECT 
        users.id, 
        users.name, 
        user_schedules.schedule_id AS schedule_id, 
        schedules.name AS schedule_name,
        user_schedules.from_date AS from_date, 
        user_schedules.to_date AS to_date
    FROM users 
    LEFT JOIN user_schedules ON user_schedules.user_id = users.id 
    LEFT JOIN schedules ON schedules.id = user_schedules.schedule_id
    WHERE users.id = :user_id LIMIT 1", ["user_id" => $_GET["user_id"]]
);

$schedules = find_all("SELECT * FROM schedules");

$users = find_all("SELECT * FROM users");
// echo "<pre>";
// print_r($user);
// echo "</pre>";
?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">EDIT USER SCHEDULE</h2>

    <div class="mb-6">
        <label for="user_name" class="mb-1 block">User</label>

        <select class="form-control" name="user_id" id="user_id">
            <?php foreach($users as $l_user): ?>
                <option <?= $l_user["id"] == $user["id"] ? "selected" : "" ?> value="<?= $l_user["id"] ?>"><?= $l_user["name"] ?></option> 
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-6">
        <label for="out_time" class="mb-1 block">Schedule</label>

        <select class="form-control" name="schedule_id" id="schedule_id" required>
            <option value="">Select a schedule</option>
            <?php foreach($schedules as $l_schedule): ?>
                <?php echo $user["schedule_id"] ?>
                <option <?= $l_schedule["id"] == $user["schedule_id"] ? "selected" : "" ?> value="<?= $l_schedule["id"] ?>"><?= $l_schedule["name"] ?></option> 
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-6">
        <label for="from_date" class="mb-1 block">From date</label>
        <input type="date" name="from_date" id="from_date" class="form-control" value="<?= $user["from_date"] ?>" required>
    </div>

    <div class="mb-6">
        <label for="to_date" class="mb-1 block">To date</label>
        <input type="date" name="to_date" id="to_date" class="form-control" value="<?= $user["to_date"] ?>" required>
    </div>

    <button class="btn btn-primary">Update</button>
</form>

<?php require("footer.php") ?>