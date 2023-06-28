<?php

require_once("db-utils.php");

if($_SERVER["REQUEST_METHOD"] == "POST") 
{
  
    $sql = "
        UPDATE settings 
        SET 
            in_time = :in_time, 
            out_time = :out_time
    ";

    query($sql, [
        "in_time" => [
            $_POST["in_time"],
            PDO::PARAM_STR
        ],
        "out_time" => [
            $_POST["out_time"],
            PDO::PARAM_STR
        ]
    ]);

    die("<script>window.location.href='/multiple-punch/settings.php'</script>");
}

$settings = find_one("SELECT * FROM settings LIMIT 1");

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">EDIT SETTINGS</h2>

    <div class="mb-6">
        <label for="in_time" class="mb-1 block">In Time</label>
        <input type="time" name="in_time" id="in_time" class="form-control" value="<?= $settings["in_time"] ?>">
    </div>

    <div class="mb-6">
        <label for="out_time" class="mb-1 block">Out Time</label>
        <input type="time" name="out_time" id="out_time" class="form-control" value="<?= $settings["out_time"] ?>">
    </div>
    
    <button class="btn btn-primary">Update</button>
</form>

<?php require("footer.php") ?>