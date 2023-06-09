<?php

require_once("db-utils.php");

if(isset($_POST["punch_in_time"])) 
{
    query("DELETE FROM timings WHERE id = :id", ["id" => $_GET["id"]]);
    require("punching.php");
}

$sql = "
    SELECT 
        users.id AS user_id,
        users.name AS user_name, 
        users.email AS user_email, 
        timings.* 
    FROM timings 
    INNER JOIN users ON users.id = timings.user_id 
    WHERE timings.id = :id 
    LIMIT 1
";

$timing = find_one($sql, ["id" => $_GET["id"]]);

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">EDIT TIMING</h2>

    <input type="hidden" name="id" value="<?= $timing["id"] ?>">

    <input type="hidden" name="user_id" value="<?= $timing["user_id"] ?>">

    <div class="mb-6">
        <label for="name" class="mb-1 block">Name</label>
        <input type="text" name="name" id="name" class="form-control" disabled value="<?= $timing["user_name"] ?>">
    </div>

    <div class="mb-6">
        <label for="email" class="mb-1 block">Email</label>
        <input type="email" name="email" id="email" class="form-control" disabled value="<?= $timing["user_email"] ?>">
    </div>

    <div class="mb-6">
        <label for="punch_in_time" class="mb-1 block">Punch In Time</label>
        <input type="datetime-local" name="punch_in_time" id="punch_in_time" class="form-control" value="<?= date("Y-m-d H:i", strtotime($timing["punch_in_time"])) ?>">
    </div>

    <div class="mb-6">
        <label for="punch_out_time" class="mb-1 block">Punch Out Time</label>
        <input type="datetime-local" name="punch_out_time" id="punch_out_time" class="form-control" value="<?= date("Y-m-d H:i", strtotime($timing["punch_out_time"])) ?>">
    </div>

    <button class="btn btn-primary">Update</button>
</form>

<?php require("footer.php") ?>