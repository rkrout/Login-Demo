<?php

require_once("auth-utils.php");

not_auth_or_redirect();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(login($_POST["email"], $_POST["password"]))
    {
        die("<script>window.location.href='/multiple-punch/index.php'</script>");
    }
    else 
    {
        die("<script>alert('Invalid email or password')</script>");
    }
} 

?>

<?php require("header.php") ?>

<form method="post" class="border border-gray-300 rounded-md p-6 max-w-xl mx-auto my-8">
    <h2 class="font-bold text-center text-orange-600 text-2xl mb-6">LOGIN</h2>

    <div class="mb-6">
        <label for="email" class="mb-1 block">Email</label>
        <input type="email" name="email" id="email" class="form-control">
    </div>

    <div class="mb-6">
        <label for="password" class="mb-1 block">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <button class="btn btn-primary">Login</button>
</form>

<?php require("footer.php") ?>