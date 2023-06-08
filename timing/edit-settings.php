<?php

require("db-utils.php");
require_once("date-utils.php");

if(isset($_POST["break_time"])) {
    $sql = "
        UPDATE settings 
        SET 
            break_time = :break_time, 
            break_interval = :break_interval, 
            regular_time = :regular_time,
            punch_type = :punch_type,
            double_time = :double_time
    ";

    query($sql, [
        "break_time" => $_POST["break_time"] * 60,
        "break_interval" => $_POST["break_interval"] * 3600,
        "regular_time" => $_POST["regular_time"] * 3600,
        "punch_type" => $_POST["punch_type"],
        "double_time" => $_POST["double_time"] * 3600
    ]);

    die("<script>alert('Setting updated successfully'); window.location.href='/timing/settings.php'</script>");
}

$settings = find_one("SELECT * FROM settings LIMIT 1");

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
            <option <?= $settings["punch_type"] == "in_punch" ? "selected" : "" ?> value="in_punch">In Punch</option>
            <option <?= $settings["punch_type"] == "split_punch" ? "selected" : "" ?> value="split_punch">Split Punch</option>
            <option <?= $settings["punch_type"] == "majority_hours" ? "selected" : "" ?> value="majority_hours">Majority Hours</option>
        </select>
    </div>

    <button class="btn btn-primary">Update</button>
</form>

<?php require("footer.php") ?>