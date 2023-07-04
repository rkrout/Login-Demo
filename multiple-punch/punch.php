<?php

require_once("db-utils.php");
require_once("date-utils.php");
require_once("session-utils.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $sql = "
        SELECT 
            schedule_days.day,
            schedule_days.in_time,
            schedule_days.out_time
        FROM user_schedules
        INNER JOIN schedules ON schedules.id = user_schedules.schedule_id 
        INNER JOIN schedule_days ON schedule_days.schedule_id = schedules.id
        WHERE user_schedules.user_id = :user_id
    ";

    $days = find_all($sql, ["user_id" => $_POST["user_id"]]);

    $actual_date = get_actual_date($_POST["time"], $days);
    
    $working_day = find_one("SELECT * FROM working_days WHERE start_time = :start_time AND end_time = :end_time AND user_id = :user_id LIMIT 1", [
        "start_time" => [
            $actual_date[0],
            PDO::PARAM_STR
        ],
        "end_time" => [
            $actual_date[1],
            PDO::PARAM_STR
        ],
        "user_id" => $_POST["user_id"]
    ]);
    
    if($working_day)
    {
        $punches = find_all("SELECT * FROM punches WHERE working_day_id = " . $working_day["id"]);
    
        $total_punches = count($punches);
    
        if($total_punches == 0 || $punches[$total_punches - 1]["punch_out_time"])
        {
            insert("punches", [
                "working_day_id" => $working_day["id"],
                "punch_in_time" => date("Y-m-d H:i:s", strtotime($_POST["time"]))
            ]);
        }
        else 
        {
            $last_punch = $punches[$total_punches - 1];

            query("UPDATE punches SET punch_out_time = :punch_out_time WHERE id = :id", [
                "punch_out_time" => [
                    date("Y-m-d H:i:s", strtotime($_POST["time"])),
                    PDO::PARAM_STR
                ],
                "id" => $last_punch["id"]
            ]);
        }
    }
    else 
    {
        $last_id = insert("working_days", [
            "start_time" => $actual_date[0],
            "end_time" => $actual_date[1],
            "user_id" => $_POST["user_id"]
        ]);

        insert("punches", [
            "working_day_id" => $last_id,
            "punch_in_time" => date("Y-m-d H:i:s", strtotime($_POST["time"]))
        ]);
    }

    die("<script>window.location.href='/multiple-punch/'</script>");
} 

$users = find_all("SELECT * FROM users");

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">PUNCH</h2>

    <div class="mb-6">
        <label for="user_id" class="mb-1 block">User</label>
        <select class="form-control" name="user_id" id="user_id">
            <?php foreach($users as $user): ?>
                <option value="<?= $user["id"] ?>"><?= $user["name"] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-6">
        <label for="time" class="mb-1 block">Date Time</label>
        <input type="datetime-local" name="time" id="time" class="form-control">
    </div>

    <button class="btn btn-primary">Save</button>
</form>

<?php require("footer.php") ?>