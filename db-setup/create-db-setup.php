<?php

require_once("../utils/helper-utils.php");

if(is_post()) 
{
    file_put_contents("db-config.json", json_encode($_POST));
    redirect_with_alert("/db-setup/config.php", "Database set up successfull");
}

$config = json_decode(file_get_contents("db-config.json"), true);

?>

<?php require("../header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">SET UP DATABASE</h2>

    <div class="mb-6">
        <label for="host" class="mb-1 block">Database Host</label>
        <input type="text" name="host" id="host" class="form-control" value="<?= $config["host"] ?? "" ?>">
    </div>

    <div class="mb-6">
        <label for="name" class="mb-1 block">Database Name</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= $config["name"] ?? "" ?>">
    </div>
    
    <div class="mb-6">
        <label for="username" class="mb-1 block">Database Username</label>
        <input type="text" name="username" id="username" class="form-control" value="<?= $config["username"] ?? "" ?>">
    </div>

    <div class="mb-6">
        <label for="password" class="mb-1 block">Database Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <button class="btn btn-primary">Set Up</button>
</form>

<?php require("../footer.php") ?>