<?php
require_once("../utils/helper-utils.php");

$config = json_decode(file_get_contents("db-config.json"), true);

if(!(isset($config["host"]) && isset($config["name"]) && isset($config["username"]) && isset($config["password"])))
{
    redirect("/db-setup/create-db-setup.php");
}

?>

<?php 
    $page_title = "Database Config";
    require("../header.php") 
?>

<div class="max-w-5xl mx-auto my-5">
    <a href="/db-setup/create-db-setup.php" class="mb-4 px-4 py-2 bg-orange-600 rounded text-white">Edit Config</a>
    
    <table class="w-full border border-gray-300 rounded max-w-5xl mx-auto my-5">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Host</th>
                <th class="p-2">Database</th>
                <th class="p-2">Username</th>
                <th class="p-2">Password</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <tr class="border-t border-t-gray-300">
                <td class="p-2"><?= $config["host"] ?></td>
                <td class="p-2"><?= $config["name"] ?></td>
                <td class="p-2"><?= $config["username"] ?></td>
                <td class="p-2"><?= $config["password"] ?></td>
            </tr>
        </tbody>
    </table>
</div>
<?php require("../footer.php") ?>