<?php 
    require("db.php");
    $stmt = $pdo->prepare("select * from users where email = :email and email != :old_email limit 1");
    $stmt->bindParam("email", $_POST["email"]);
    $stmt->bindParam("old_email", $_POST["old_email"]);
    $stmt->execute();
    $user = $stmt->fetch();
    if($user){
        echo "<script>alert('Email already exists');window.location.href='/index.php'</script>";
    } else {
        $stmt = $pdo->prepare("update users set name = :name, email = :email where email = :old_email");
        $stmt->bindParam("name", $_POST["name"]);
        $stmt->bindParam("email", $_POST["email"]);
        $stmt->bindValue("old_email", $_POST["old_email"]);
        $stmt->execute();
        echo "<script>alert('User updated successfully');window.location.href='/index.php'</script>";
    }