<?php
session_start();
if(isset($_POST["oldPassword"])){
    require("db.php");

    $stmt = $pdo->prepare("select * from users where email = :email limit 1");
    $stmt->bindParam("email", $_SESSION["userId"]);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($_POST["newPassword"] != $_POST["confirmNewPassword"]){
        echo "<script>alert('Password mismatch')</script>";
    } else {
        if(password_verify($_POST["oldPassword"], $user["password"])){
            $stmt = $pdo->prepare("update users set password = :password where email = :email");
            $stmt->bindParam("email", $_SESSION["userId"]);
            $stmt->bindValue("password", password_hash($_POST["newPassword"], PASSWORD_BCRYPT));
            $stmt->execute();
            echo "<script>alert('Password changed successfully')</script>";
        } else {
            echo "<script>alert('Old password does not match')</script>";
        }
    }
}

?>


<?php require("header.php") ?>

<form action="/change-password.php" method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">Change Password</h2>
   <div class="mb-6">
   <label for="oldPassword" class="mb-1 block">Old Password</label>
    <input type="password" name="oldPassword" id="oldPassword" class="border border-gray-300 rounded px-4 py-2 w-full">
   </div>
   <div class="mb-6">
   <label for="newPassword" class="mb-1 block">New Password</label>
    <input type="password" name="newPassword" id="newPassword" class="border border-gray-300 rounded px-4 py-2 w-full">
   </div>
   <div class="mb-6">
   <label for="confirmNewPassword" class="mb-1 block">Confirm New Password</label>
    <input type="password" name="confirmNewPassword" id="confirmNewPassword" class="border border-gray-300 rounded px-4 py-2 w-full">
   </div>
   <div class="flex gap-1">
   <a class="px-4 py-2 bg-gray-600 rounded text-white" href="index.php">Go Back</a>
   <button class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 focus:ring-1 focus:ring-orange-600 focus:ring-offset-1">Change Password</button>

   </div>
</form>

<?php require("footer.php") ?>