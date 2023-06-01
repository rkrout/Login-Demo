<?php 
require("db.php");
$stmt = $pdo->prepare("delete from users where email = :email");
$stmt->bindParam("email", $_POST["email"]);
$stmt->execute();
echo "<script>alert('User deleted successfully');window.location.href='/index.php'</script>";