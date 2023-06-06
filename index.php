<?php

session_start();

require("check-is-logged-in.php");
require("db.php");

$stmt = $pdo->prepare("select * from users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php 
    $title = "Home";
    require("header.php") 
?>
<div class="max-w-5xl mx-auto my-5">
    <a href="create-user.php" class="mb-4 px-4 py-2 bg-orange-600 rounded text-white">Add New User</a>
    
    <table class="w-full border border-gray-300 rounded max-w-5xl mx-auto my-5">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Name</th>
                <th class="p-2">Email</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php foreach($users as $user): ?>
                <tr class="border-t border-t-gray-300">
                    <td class="p-2"><?= $user["name"] ?></td>
                    <td class="p-2"><?= $user["email"] ?></td>
                    <td class="p-2">
                        <div class="justify-center flex gap-1">
                            <a class="px-2 py-1 rounded bg-yellow-600 text-white" href="edit-user.php?id=<?= $user["id"] ?>">Edit</a>
                            
                            <form class="px-2 py-1 rounded bg-red-600 text-white" action="delete-user.php" method="post">
                                <input type="hidden" name="id" value="<?= $user["id"] ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require("footer.php") ?>