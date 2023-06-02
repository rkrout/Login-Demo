<?php 
require("db.php");
$stmt = $pdo->prepare("delete from users where id = :id");
$stmt->bindParam("id", $_POST["id"]);
$stmt->execute();
echo "<script>alert('User deleted successfully');window.location.href='/index.php'</script>";