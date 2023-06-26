<?php

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    require_once("db-utils.php");
    require_once("session-utils.php");

    $last_punch = find_one("SELECT * FROM timings WHERE user_id = ". get_session("user_id") ." AND date = '". date("Y-m-d", strtotime($_POST["date"])) ."' ORDER BY id DESC LIMIT 1");

    if($last_punch && $last_punch["is_punch_in"])
    {
        if(strtotime($_POST["punch_out_time"]) > strtotime($last_punch["punch_in_time"]))
        {
            die("<script>alert('Punch out time must be bigger than punch in time'); window.location.href='/multiple-punch/index.php'</script>");
        }

        query("UPDATE timings SET punch_out_time = :punch_out_time, is_punch_in = false WHERE id = " . $last_punch["id"], [
            "punch_out_time" => $_POST["time"]
        ]);
    }
    else 
    {
        if($last_punch && strtotime($last_punch["punch_out_time"]) > strtotime($_POST["time"]))
        {
            die("<script>alert('Invalid punch in time'); window.location.href='/multiple-punch/index.php'</script>");
        }

        insert("timings", [
            "user_id" => get_session("user_id"),
            "punch_in_time" => $_POST["time"],
            "is_punch_in" => true,
            "date" => $_POST["date"]
        ]);
    }
} 

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">PUNCH</h2>

    <div class="mb-6">
        <label for="date" class="mb-1 block">Date</label>
        <input type="date" name="date" id="date" class="form-control">
    </div>

    <div class="mb-6">
        <label for="time" class="mb-1 block">Time</label>
        <input type="time" name="time" id="time" class="form-control">
    </div>

    <button class="btn btn-primary">Save</button>
</form>

<?php require("footer.php") ?>