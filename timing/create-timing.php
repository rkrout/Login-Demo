<?php

require("db.php");

if(isset($_POST["punch_in_time"])) {
    require("split-punching.php");
}

$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">INSERT TIMING</h2>

    <input type="hidden" name="user_id" value="<?= $timing["user_id"] ?>">

    <div class="mb-6">
        <label for="user_id" class="mb-1 block">User</label>
        <select name="user_id" id="user_id" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none">
            <?php foreach($users as $user): ?>
                <option value="<?= $user["id"] ?>"><?= $user["name"] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-6">
        <label for="punch_in_time" class="mb-1 block">Punch In Time</label>
        <input type="datetime-local" name="punch_in_time" id="punch_in_time" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none">
    </div>

    <div class="mb-6">
        <label for="punch_out_time" class="mb-1 block">Punch Out Time</label>
        <input type="datetime-local" name="punch_out_time" id="punch_out_time" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none">
    </div>

    <button class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 
    focus:ring-1 focus:ring-orange-600 focus:ring-offset-1">Save</button>
</form>

<?php require("footer.php") ?>