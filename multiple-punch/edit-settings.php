<?php

require_once("db-utils.php");
require_once("date-utils.php");

if(isset($_POST["break_time"])) 
{
    $sql = "
        UPDATE settings 
        SET 
            break_time = :break_time, 
            break_interval = :break_interval, 
            regular_time = :regular_time,
            punch_type = :punch_type,
            double_time = :double_time,
            consecutive_days = :consecutive_days,
            week_start_day = :week_start_day,
            over_time_cal = :over_time_cal,
            weekly_over_time = :weekly_over_time,
            double_time_cal = :double_time_cal,
            weekly_double_time = :weekly_double_time
    ";

    query($sql, [
        "break_time" => $_POST["break_time"] * 60,
        "break_interval" => $_POST["break_interval"] * 3600,
        "regular_time" => $_POST["regular_time"] * 3600,
        "punch_type" => $_POST["punch_type"],
        "double_time" => $_POST["double_time"] * 3600,
        "consecutive_days" => $_POST["consecutive_days"],
        "week_start_day" => $_POST["week_start_day"],
        "over_time_cal" => $_POST["over_time_cal"],
        "weekly_over_time" => $_POST["weekly_over_time"] * 3600,
        "double_time_cal" => $_POST["double_time_cal"],
        "weekly_double_time" => $_POST["weekly_double_time"] * 3600
    ]);

    die("<script>window.location.href='/multiple-punch/settings.php'</script>");
}

$settings = find_one("SELECT * FROM settings LIMIT 1");

// echo "<pre>";
// print_r($settings);
// echo "</pre>";
// die;

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">EDIT SETTINGS</h2>

    <div class="mb-6">
        <label for="break_time" class="mb-1 block">Break Time (in minute)</label>
        <input type="number" name="break_time" id="break_time" class="form-control" value="<?= get_sec_to_minute($settings["break_time"]) ?>">
    </div>
    
    <div class="mb-6">
        <label for="break_interval" class="mb-1 block">Break Interval (in hour)</label>
        <input type="number" name="break_interval" id="break_interval" class="form-control" value="<?= get_sec_to_hour($settings["break_interval"]) ?>">
    </div>
    
    <div class="mb-6">
        <label for="regular_time" class="mb-1 block">Regular Time (in hour)</label>
        <input type="number" name="regular_time" id="regular_time" class="form-control" value="<?= get_sec_to_hour($settings["regular_time"]) ?>">
    </div>

    <div class="mb-6">
        <label for="double_time" class="mb-1 block">Double Time (in hour)</label>
        <input type="number" name="double_time" id="double_time" class="form-control" value="<?= get_sec_to_hour($settings["double_time"]) ?>">
    </div>

    <div class="mb-6">
        <label for="punch_type" class="mb-1 block">Punch Type</label>
        <select name="punch_type" id="punch_type" class="form-control">
            <option <?= $settings["punch_type"] == "In Punch" ? "selected" : "" ?> value="In Punch">In Punch</option>
            <option <?= $settings["punch_type"] == "Split Punch" ? "selected" : "" ?> value="Split Punch">Split Punch</option>
            <option <?= $settings["punch_type"] == "Majority Hours" ? "selected" : "" ?> value="Majority Hours">Majority Hours</option>
        </select>
    </div>

    <div class="mb-6">
        <label for="consecutive_days" class="mb-1 block">Consecutive Days</label>
        <input type="number" name="consecutive_days" id="consecutive_days" class="form-control" value="<?= $settings["consecutive_days"] ?>">
    </div>

    <div class="mb-6">
        <label for="week_start_day" class="mb-1 block">Week Start Day</label>
        <select name="week_start_day" id="week_start_day" class="form-control">
            <option <?= $settings["week_start_day"] == "Monday" ? "selected" : "" ?>>Monday</option>
            <option <?= $settings["week_start_day"] == "Tuesday" ? "selected" : "" ?>>Tuesday</option>
            <option <?= $settings["week_start_day"] == "Wednesday" ? "selected" : "" ?>>Wednesday</option>
            <option <?= $settings["week_start_day"] == "Thursday" ? "selected" : "" ?>>Thursday</option>
            <option <?= $settings["week_start_day"] == "Friday" ? "selected" : "" ?>>Friday</option>
            <option <?= $settings["week_start_day"] == "Saturday" ? "selected" : "" ?>>Saturday</option>
            <option <?= $settings["week_start_day"] == "Sunday" ? "selected" : "" ?>>Sunday</option>
        </select>
    </div>

    <div class="mb-6">
        <label for="over_time_cal" class="mb-1 block">Calculate over time</label>

        <div class="flex gap-2 cursor-pointer">
            <input type="radio" class="h-4 w-4" <?= $settings["over_time_cal"] == "Daily" ? "checked" : "" ?> name="over_time_cal" id="over_time_daily" value="Daily">
            <label for="over_time_daily">Daily</label>
        </div>
        
        <div class="flex gap-2 cursor-pointer">
            <input type="radio" class="h-4 w-4" <?= $settings["over_time_cal"] == "Weekly" ? "checked" : "" ?> name="over_time_cal" id="over_time_weekly" value="Weekly">
            <label for="over_time_weekly">Weekly</label>
        </div>
    </div>

    <div class="mb-6">
        <label for="weekly_over_time" class="mb-1 block">Weekly over time (In hour)</label>
        <input type="number" name="weekly_over_time" id="weekly_over_time" class="form-control" value="<?= get_sec_to_hour($settings["weekly_over_time"]) ?>">
    </div>

    <div class="mb-6">
        <label for="double_time_cal" class="mb-1 block">Calculate double time</label>

        <div class="form-check">
            <input type="radio" class="form-check-input" <?= $settings["double_time_cal"] == "Daily" ? "checked" : "" ?> name="double_time_cal" id="double_time_daily" value="Daily">
            <label for="double_time_daily">Daily</label>
        </div>

        <div class="form-check mt-1">
            <input type="radio" class="form-check-input" <?= $settings["double_time_cal"] == "Weekly" ? "checked" : "" ?> name="double_time_cal" id="double_time_weekly" value="Weekly">
            <label for="double_time_weekly">Weekly</label>
        </div>
    </div>

    <div class="mb-6">
        <label for="weekly_double_time" class="mb-1 block">Weekly double time (In hour)</label>
        <input type="number" name="weekly_double_time" id="weekly_double_time" class="form-control" value="<?= get_sec_to_hour($settings["weekly_double_time"]) ?>">
    </div>

    <button class="btn btn-primary">Update</button>
</form>

<?php require("footer.php") ?>