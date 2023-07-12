<?php 

$db = new PDO("mysql:host=localhost;dbname=students", "root", "");

if($_GET["action"] == "get_students")
{
    $stmt = $db->prepare("select * from students");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($students);
}
else if($_GET["action"] == "create_student")
{
    error_reporting(1);
    file_put_contents("demo.json", json_encode($_POST));
    echo json_encode(["message" => "Student created successfully"]);
die();
    $image_path = "uploads/" . bin2hex(random_bytes(16)) . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    $stmt = $db->prepare("insert into students (name, email, phone, image_url, active) values (:name, :email, :phone, :image_url, :active)");
    $stmt->bindParam("name", $_POST["name"]);
    $stmt->bindParam("email", $_POST["email"]);
    $stmt->bindParam("phone", $_POST["phone"]);
    $stmt->bindValue("active", $_POST["active"] == "true");
    $stmt->bindParam("image_url", $image_path);
    $stmt->execute();
    echo json_encode(["message" => "Student created successfully"]);
}
else if($_GET["action"] == "get_student")
{
    file_put_contents("demo.json", json_encode($_POST));
    $stmt = $db->prepare("select * from students where id = :id limit 1");
    $stmt->bindParam("id", $_GET["id"]);
    $stmt->execute();
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}
else if($_GET["action"] == "update_student")
{
    file_put_contents("demo.json", json_encode($_POST));
    $stmt = $db->prepare("update students set name = :name, email = :email, phone = :phone where id = :id");
    $stmt->bindParam("id", $_POST["id"]);
    $stmt->bindParam("name", $_POST["name"]);
    $stmt->bindParam("email", $_POST["email"]);
    $stmt->bindParam("phone", $_POST["phone"]);
    $stmt->execute();
    echo json_encode(["message" => "Student updated successfully"]);
}
else if($_GET["action"] == "delete_student")
{
    $stmt = $db->prepare("delete from students where id = :id");
    $stmt->bindParam("id", $_POST["id"]);
    $stmt->execute();
    echo json_encode(["message" => "Student deleted successfully"]);
}