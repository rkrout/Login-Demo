<?php

session_start();
require("check-is-logged-in.php");
if(isset($_POST["name"])) {
    require("db.php");
    $stmt = $pdo->prepare("select * from users where email = :email limit 1");
    $stmt->bindParam("email", $_POST["email"]);
    $stmt->execute();
    $user = $stmt->fetch();
    if($user){
        echo "<script>alert('Email already exists')</script>";
    } else {
        $stmt = $pdo->prepare("insert into users (name, email, password) values (:name, :email, :password)");
        $stmt->bindParam("name", $_POST["name"]);
        $stmt->bindParam("email", $_POST["email"]);
        $stmt->bindValue("password", password_hash($_POST["password"], PASSWORD_BCRYPT));
        $stmt->execute();
        echo "<script>alert('User created successfully');window.location.href='/index.php'</script>";
    }
}

?>



<?php require("header.php") ?>

<form action="/create-user.php" method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">Add User</h2>
    <div class="mb-6">
   <label for="name" class="mb-1 block">Name</label>
    <input type="text" name="name" id="name" class="border border-gray-300 rounded px-4 py-2 w-full">
   </div>
   <div class="mb-6">
   <label for="email" class="mb-1 block">Email</label>
    <input type="text" name="email" id="email" class="border border-gray-300 rounded px-4 py-2 w-full">
   </div>
   <div class="mb-6">
   <label for="password" class="mb-1 block">Password</label>
    <input type="password" name="password" id="password" class="border border-gray-300 rounded px-4 py-2 w-full">
   </div>
   <div class="flex gap-1">
   <a class="px-4 py-2 bg-gray-600 rounded text-white" href="index.php">Go Back</a>

<input type="submit" name="submit" class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 focus:ring-1 focus:ring-orange-600 focus:ring-offset-1" value="Save">
   </div>
</form>

<?php require("footer.php") ?>