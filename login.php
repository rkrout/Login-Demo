<?php

session_start();
require("check-is-not-logged-in.php");
if(isset($_POST["email"])) {
    require("db.php");
    $stmt = $pdo->prepare("select * from users where email = :email limit 1");
    $stmt->bindParam("email", $_POST["email"]);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if($user && password_verify($_POST["password"], $user["password"])) {
        $_SESSION["userId"] = $user["id"];
        $_SESSION["name"] = $user["name"];
        header("Location: index.php");
        die; 
    } else {
        echo "<script>alert('Invalid email or password')</script>";
    }
}

?>


<?php require("header.php") ?>

<form action="/login.php" method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">Login</h2>
   <div class="mb-6">
   <label for="email" class="mb-1 block">Email</label>
    <input type="text" name="email" id="email" class="border border-gray-300 rounded px-4 py-2 w-full">
   </div>
   <div class="mb-6">
   <label for="password" class="mb-1 block">Password</label>
    <input type="password" name="password" id="password" class="border border-gray-300 rounded px-4 py-2 w-full">
   </div>
   <button class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 focus:ring-1 focus:ring-orange-600 focus:ring-offset-1">Login</button>
</form>

<?php require("footer.php") ?>