<?php

require_once("../utils/helper-utils.php");
require_once("../utils/auth-utils.php");
require_once("../utils/db-utils.php");

if(is_post()) 
{
    $user = find_one("SELECT * FROM users WHERE email = :email LIMIT 1", [
        "email" => $_POST["email"]
    ]);

    if($user && password_verify($_POST["password"], $user["password"])) 
    {
        put_session("user_id", $user["id"]);
        put_session("user_name", $user["name"]);
        put_session("user_email", $user["email"]);
        redirect("/index.php");
    } 
    else 
    {
        show_alert("Invalid email or password");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
        <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">Login</h2>

        <div class="mb-6">
            <label for="email" class="mb-1 block">Email</label>
            <input type="text" name="email" id="email" class="form-control">
        </div>

        <div class="mb-6">
            <label for="password" class="mb-1 block">Password</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <button class="btn btn-primary">Login</button>
    </form>
</body>
</html>