<?php 

if(!isset($_SESSION["user_id"])) 
{
    die("<script>window.location.href='/login.php'</script>");
}