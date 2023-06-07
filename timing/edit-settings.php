<?php

require("db.php");

$stmt = $pdo->prepare("SELECT * FROM settings LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST["break_time"])) {
    $stmt = $pdo->prepare("
        UPDATE settings 
        SET 
            break_time = :break_time, 
            break_interval = :break_interval, 
            regular_time = :regular_time,
            is_split_punch = :is_split_punch,
            double_time = :double_time
    ");
    $stmt->bindParam("break_time", $_POST["break_time"]);
    $stmt->bindParam("break_interval", $_POST["break_interval"]);
    $stmt->bindParam("regular_time", $_POST["regular_time"]);
    $stmt->bindParam("is_split_punch", $_POST["is_split_punch"]);
    $stmt->bindParam("double_time", $_POST["double_time"]);
    $stmt->execute();
    die("<script>alert('Setting updated successfully'); window.location.href='/timing/settings.php'</script>");
}

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">EDIT SETTINGS</h2>

    <div class="mb-6">
        <label for="break_time" class="mb-1 block">Break Time (in minute)</label>
        <input type="number" name="break_time" id="break_time" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= $settings["break_time"] ?>">
    </div>

    <div class="mb-6">
        <label for="break_interval" class="mb-1 block">Break Interval (in hour)</label>
        <input type="number" name="break_interval" id="break_interval" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= $settings["break_interval"] ?>">
    </div>
    
    <div class="mb-6">
        <label for="regular_time" class="mb-1 block">Regular Time</label>
        <input type="number" name="regular_time" id="regular_time" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= $settings["regular_time"] ?>">
    </div>

    <div class="mb-6">
        <label for="double_time" class="mb-1 block">Double Time (in hour)</label>
        <input type="number" name="double_time" id="double_time" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= $settings["double_time"] ?>">
    </div>

    <div class="mb-6">
        <label for="is_split_punch" class="mb-1 block">Punch Type</label>
        <select name="is_split_punch" id="is_split_punch" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none">
            <option <?= $settings["is_split_punch"] == 0 ? "selected" : "" ?> value="0">In Punch</option>
            <option <?= $settings["is_split_punch"] ? "selected" : "" ?> value="1">Split Punch</option>
        </select>
    </div>

    <button class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 
    focus:ring-1 focus:ring-orange-600 focus:ring-offset-1">Update</button>
</form>

<?php require("footer.php") ?>