<?php

require_once("../utils/helper-utils.php");
require_once("../utils/auth-utils.php");
require_once("../utils/db-utils.php");

if(is_post()){

    $user = find_one("SELECT * FROM users WHERE id = :id LIMIT 1");

    if($_POST["newPassword"] != $_POST["confirmNewPassword"])
    {
        show_alert("Password Mismatch");
    } 
    else 
    {
        if(password_verify($_POST["oldPassword"], $user["password"]))
        {
            query("UPDATE users SET password = :password WHERE email = :email", [
                "email" => get_session("user_id"),
                "password" => password_hash($_POST["newPassword"], PASSWORD_BCRYPT)
            ]);
            show_alert("Password changed successfully");
        } 
        else 
        {
            show_alert("Old password does not match");
        }
    }
}

?>

<?php require("../header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">Change Password</h2>

    <div class="mb-6">
        <label for="oldPassword" class="mb-1 block">Old Password</label>
        <input type="password" name="oldPassword" id="oldPassword" class="form-control">
    </div>

    <div class="mb-6">
        <label for="newPassword" class="mb-1 block">New Password</label>
        <input type="password" name="newPassword" id="newPassword" class="form-control">
    </div>

    <div class="mb-6">
        <label for="confirmNewPassword" class="mb-1 block">Confirm New Password</label>
        <input type="password" name="confirmNewPassword" id="confirmNewPassword" class="form-control">
    </div>

    <div class="flex gap-1">
        <a class="px-4 py-2 bg-gray-600 rounded text-white" href="/index.php">Go Back</a>
        <button class="btn btn-primary">Change Password</button>
    </div>
</form>

<?php require("../footer.php") ?>