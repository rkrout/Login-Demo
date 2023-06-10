<?php 

session_start();

function put_session($key, $value) 
{
    $_SESSION[$key] = $value;
}

function get_session($key) 
{
    return $_SESSION[$key] ?? "";
}

function get_flash_session($key) 
{
    $data = $_SESSION[$key];

    unset($key);

    return $data;
}