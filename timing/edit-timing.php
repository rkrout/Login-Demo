<?php

require("db.php");

$stmt = $pdo->prepare("
    SELECT 
        users.name AS user_name, 
        users.email AS user_email, 
        timings.* 
    FROM timings 
    INNER JOIN users ON users.id = timings.user_id 
    WHERE timings.id = :id 
    LIMIT 1
");
$stmt->bindParam("id", $_GET["id"]);
$stmt->execute();
$timing = $stmt->fetch(PDO::FETCH_ASSOC);

if(isset($_POST["punch_in_time"])) {
    // fetching setting information
    $stmt = $pdo->prepare("SELECT * FROM settings LIMIT 1");
    $stmt->execute();
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);

    $working_time_in_second = strtotime($_POST["punch_out_time"]) - strtotime($_POST["punch_in_time"]);

    $regular_time_in_second = $settings["regular_time"] * 3600;

    // calculating break time
    $break_time_in_second = 60 * $settings["break_time"];

    $total_break = intdiv($working_time_in_second, $settings["break_interval"] * 3600);
    
    $total_break_time_in_second = $total_break * $break_time_in_second;

    $total_break_time = $total_break_time_in_second / 60;

    // calculating over time
    if($working_time_in_second > $regular_time_in_second) {
        $over_time_in_second = $working_time_in_second - $regular_time_in_second;
    
        $over_time = ($over_time_in_second - $total_break_time_in_second) / 60;
    } else {
        $over_time = 0;
    }

    // update timing record
    $stmt = $pdo->prepare("
        UPDATE timings
        SET
            punch_in_time = :punch_in_time,
            punch_out_time = :punch_out_time,
            total_break_time = :total_break_time,
            overtime = :over_time
        WHERE id = :id
    ");
    $stmt->bindParam("id", $_GET["id"]);
    $stmt->bindParam("punch_in_time", $_POST["punch_in_time"]);
    $stmt->bindParam("punch_out_time", $_POST["punch_out_time"]);
    $stmt->bindParam("total_break_time", $total_break_time);
    $stmt->bindParam("over_time", $over_time);
    $stmt->execute();

    // redirect user to index page
    die("<script>alert('Data edited successfully'); window.location.href='/timing/index.php'</script>");
}

?>


<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">EDIT TIMING</h2>

    <div class="mb-6">
        <label for="name" class="mb-1 block">Name</label>
        <input type="text" name="name" id="name" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none disabled:bg-gray-200" disabled value="<?= $timing["user_name"] ?>">
    </div>

    <div class="mb-6">
        <label for="email" class="mb-1 block">Email</label>
        <input type="email" name="email" id="email" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none disabled:bg-gray-200" disabled value="<?= $timing["user_email"] ?>">
    </div>

    <div class="mb-6">
        <label for="punch_in_time" class="mb-1 block">Punch In Time</label>
        <input type="datetime-local" name="punch_in_time" id="punch_in_time" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= date("Y-m-d H:i", strtotime($timing["punch_in_time"])) ?>">
    </div>

    <div class="mb-6">
        <label for="punch_out_time" class="mb-1 block">Punch Out Time</label>
        <input type="datetime-local" name="punch_out_time" id="punch_out_time" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= date("Y-m-d H:i", strtotime($timing["punch_out_time"])) ?>">
    </div>

    <button class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 
    focus:ring-1 focus:ring-orange-600 focus:ring-offset-1">Update</button>
</form>

<?php require("footer.php") ?>