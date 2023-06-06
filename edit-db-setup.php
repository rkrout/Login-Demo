<?php

if(isset($_POST["host"])) {
    file_put_contents("db-config.json", json_encode($_POST));
    echo "<script>alert('Database setup edited successfull'); window.location.href='/config.php'</script>";
    die();
}

$config = json_decode(file_get_contents("db-config.json"));

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">EDIT DATABASE CONFIG</h2>

    <div class="mb-6">
        <label for="host" class="mb-1 block">Database Host</label>
        <input type="text" name="host" id="host" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= $config->host ?>">
    </div>

    <div class="mb-6">
        <label for="name" class="mb-1 block">Database Name</label>
        <input type="text" name="name" id="name" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= $config->name ?>">
    </div>
    
    <div class="mb-6">
        <label for="username" class="mb-1 block">Database Username</label>
        <input type="text" name="username" id="username" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= $config->username ?>">
    </div>

    <div class="mb-6">
        <label for="password" class="mb-1 block">Database Password</label>
        <input type="password" name="password" id="password" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" value="<?= $config->password ?>">
    </div>

    <button class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 
    focus:ring-1 focus:ring-orange-600 focus:ring-offset-1">Save</button>
</form>

<?php require("footer.php") ?>