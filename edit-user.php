<?php

session_start();
require("check-is-logged-in.php");
require("db.php");

if(isset($_POST["name"])) {
   require("db.php");

   $stmt = $pdo->prepare("select * from users where email = :email and id != :id limit 1");
   $stmt->bindParam("email", $_POST["email"]);
   $stmt->bindParam("id", $_GET["id"]);
   $stmt->execute();

   if($stmt->fetch()){
       die("<script>alert('Email already exists'); window.location.href = '/index.php'</script>");
   }

   $stmt = $pdo->prepare("update users set name = :name, email = :email where id = :id");
   $stmt->bindParam("name", $_POST["name"]);
   $stmt->bindParam("email", $_POST["email"]);
   $stmt->bindValue("id", $_GET["id"]);
   $stmt->execute();
   echo "<script>alert('User updated successfully'); window.location.href = '/index.php'</script>";
}

$stmt = $pdo->prepare("select * from users where id = :id limit 1");
$stmt->bindParam("id", $_GET["id"]);
$stmt->execute();
$user = $stmt->fetch();

?>

<?php require("header.php") ?>

<form action="/update-user.php" method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
   <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">Edit User</h2>
   
   <div class="mb-6">
      <label for="name" class="mb-1 block">Name</label>
      <input type="text" name="name" id="name" value="<?= $user["name"] ?>" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
      focus:ring-1 focus:border-orange-600 outline-none disabled:bg-gray-200">
   </div>

   <div class="mb-6">
      <label for="email" class="mb-1 block">Email</label>
      <input type="text" name="email" id="email" value="<?= $user["email"] ?>" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
      focus:ring-1 focus:border-orange-600 outline-none disabled:bg-gray-200">
   </div>

   <div class="flex gap-1">
      <a class="px-4 py-2 bg-gray-600 rounded text-white" href="index.php">Go Back</a>

      <button class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 
      focus:ring-1 focus:ring-orange-600 focus:ring-offset-1"></buttpn>
   </div>
</form>

<?php require("footer.php") ?>