<?php

require_once("date-utils.php");
require_once("db-utils.php");

if(isset($_POST["punch_in_time"])) 
{
    require_once("punching.php");
}

$users = find_all("SELECT * FROM users");

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">INSERT TIMING</h2>

    <div class="mb-6">
        <label for="user_id" class="mb-1 block">User</label>
        <select name="user_id" id="user_id" class="form-control">
            <?php foreach($users as $user): ?>
                <option value="<?= $user["id"] ?>"><?= $user["name"] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-6">
        <label for="punch_in_time" class="mb-1 block">Punch In Time</label>
        <input type="datetime-local" name="punch_in_time" id="punch_in_time" class="form-control">
    </div>

    <div class="mb-6">
        <label for="punch_out_time" class="mb-1 block">Punch Out Time</label>
        <input type="datetime-local" name="punch_out_time" id="punch_out_time" class="form-control">
    </div>

    <button class="btn btn-primary">Save</button>
</form>

<?php require("footer.php") ?>