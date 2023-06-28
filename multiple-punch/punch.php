<?php

require_once("db-utils.php");
require_once("session-utils.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $working_day = find_one("SELECT * from working_days WHERE start_time <= :time AND end_time >= :time", [
        "time" => [
            $_POST["time"],
            PDO::PARAM_STR
        ]
    ]);
    
    if(!$working_day)
    {
        $working_days = find_all("SELECT * FROM working_days");
    
        $smallest_diff = INF;
    
        foreach ($working_days as $l_working_day) 
        {
            $diff = abs(strtotime($l_working_day["start_time"]) - strtotime($_POST["time"]));
    
            if($diff < $smallest_diff)
            {
                $smallest_diff = $diff;
                $working_day = $l_working_day;
            }
    
            $diff = abs(strtotime($l_working_day["end_time"]) - strtotime($_POST["time"]));
    
            if($diff < $smallest_diff)
            {
                $smallest_diff = $diff;
                $working_day = $l_working_day;
            }
        }
    }
    
    $punches = find_all("SELECT * FROM punches WHERE working_day_id = :working_day_id", [
        "working_day_id" => $working_day["id"]
    ]);
    
    $punch_count = count($punches);
    
    if($punch_count == 0 || $punches[$punch_count - 1]["punch_out_time"])
    {
        query("INSERT INTO punches (punch_in_time, working_day_id) VALUES (:punch_in_time, :working_day_id)", [
            "punch_in_time" => [
                $_POST["time"],
                PDO::PARAM_STR
            ],
            "working_day_id" => $working_day["id"]
        ]);
    }
    else 
    {
        query("UPDATE punches SET punch_out_time = :punch_out_time WHERE working_day_id = :working_day_id", [
            "punch_out_time" => [
                $_POST["time"],
                PDO::PARAM_STR
            ],
            "working_day_id" => $working_day["id"]
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