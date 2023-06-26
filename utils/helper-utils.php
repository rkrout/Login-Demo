<?php 

function die_and_dump($data) 
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function redirect($url) 
{
    die("<script>window.location.href='$url'</script>");
}

function redirect_with_alert($url, $msg) 
{
    die("<script>alert('$msg');window.location.href='$url'</script>");
}

function is_post() 
{
    return $_SERVER["REQUEST_METHOD"] == "POST";
}

function is_get() 
{
    return $_SERVER["REQUEST_METHOD"] == "GET";
}

function show_alert($msg) 
{
    echo("<script>alert('$msg')</script>");
}