<?php 

require_once("session-utils.php");
require_once("db-utils.php");

function login($email, $password) 
{
    $user = find_one("SELECT * FROM users WHERE email = :email LIMIT 1", [
        "email" => $email
    ]);

    if($user && password_verify($password, $user["password"]))
    {
        put_session("user_id", $user["id"]);
        put_session("user_name", $user["name"]);

        return true;
    }
    else 
    {
        return false;
    }
}

function auth_or_redirect()
{
    if(!get_session("user_id"))
    {
        die("<script>window.location.href='/auth/login.php'</script>");
    }
}

function not_auth_or_redirect()
{
    if(get_session("user_id"))
    {
        die("<script>window.location.href='/multiple-punch/index.php'</script>");
    }
}

function is_authenticated() 
{
    return isset($_SESSION["user_id"]);    
}