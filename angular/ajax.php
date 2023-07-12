<?php

error_reporting(1);

$db = new PDO("mysql:host=localhost;dbname=students", "root", "");

switch ($_GET["action"]) 
{
    case "get_students":
        $stmt = $db->prepare("select * from students");
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
    
        foreach($students as $student)
        {
            $stmt = $db->prepare("select courses.name, courses.id from student_courses inner join courses on courses.id = student_courses.course_id where student_id = " . $student["id"]);
            $stmt->execute();
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $course_names = [];

            foreach ($courses as $course) 
            {
                array_push($course_names, $course["name"]);
            }

            array_push($data, [
                "id" => (int)$student["id"],
                "name" => $student["name"],
                "email" => $student["email"],
                "phone" => (int)$student["phone"],
                "gender" => $student["gender"],
                "image_url" => $student["image_url"],
                "address" => $student["address"],
                "is_active" => $student["is_active"] == "1",
                "courses" => $course_names
            ]);
        }

        header("Content-Type: application/json");
        echo json_encode($data);
        break;
    
    case "create_student":
        if(isset($_FILES["image"]))
        {
            $image_path = "uploads/" . bin2hex(random_bytes(16)) . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        }
        else 
        {
            $image_path = Null;
        }
    
        $stmt = $db->prepare("insert into students (name, email, phone, image_url, is_active, gender, address) values (:name, :email, :phone, :image_url, :is_active, :gender, :address)");
        $stmt->bindParam("name", $_POST["name"]);
        $stmt->bindParam("email", $_POST["email"]);
        $stmt->bindParam("phone", $_POST["phone"]);
        $stmt->bindValue("is_active", $_POST["is_active"] == "true");
        $stmt->bindParam("image_url", $image_path);
        $stmt->bindParam("gender", $_POST["gender"]);
        $stmt->bindParam("address", $_POST["address"]);
        $stmt->execute();
        $student_id = $db->lastInsertId();
    
        foreach ($_POST["course_ids"] as $course_id) 
        {
            $stmt = $db->prepare("insert into student_courses (student_id, course_id) values (:student_id, :course_id)");
            $stmt->bindParam("student_id", $student_id);
            $stmt->bindParam("course_id", $course_id);
            $stmt->execute();
        }

        header("Content-Type: application/json");
        echo json_encode(["message" => "Student created successfully"]);
        break;

    case "update_student":
        file_put_contents("demo.json", json_encode($_POST));
        $stmt = $db->prepare("select * from students where id = :id");
        $stmt->bindParam("id", $_POST["id"]);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if(isset($_FILES["image"]))
        {
            $image_path = "uploads/" . bin2hex(random_bytes(16)) . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
        }
        else 
        {
            $image_path = $student["image_url"];
        }

        $stmt = $db->prepare("update students set name = :name, email = :email, phone = :phone, is_active = :is_active, gender = :gender, address = :address, image_url = :image_url where id = :id");
        $stmt->bindParam("id", $_POST["id"]);
        $stmt->bindParam("name", $_POST["name"]);
        $stmt->bindParam("email", $_POST["email"]);
        $stmt->bindParam("phone", $_POST["phone"]);
        $stmt->bindValue("is_active", $_POST["is_active"] == "true");
        $stmt->bindParam("image_url", $image_path);
        $stmt->bindParam("gender", $_POST["gender"]);
        $stmt->bindParam("address", $_POST["address"]);
        $stmt->execute();

        $stmt = $db->prepare("delete from student_courses where student_id = :student_id");
        $stmt->bindParam("student_id", $_POST["id"]);
        $stmt->execute();

        foreach ($_POST["course_ids"] as $course_id) 
        {
            $stmt = $db->prepare("insert into student_courses (student_id, course_id) values (:student_id, :course_id)");
            $stmt->bindParam("student_id", $_POST["id"]);
            $stmt->bindParam("course_id", $course_id);
            $stmt->execute();
        }

        header("Content-Type: application/json");
        echo json_encode(["message" => "Student updated successfully"]);
        break;

    case "delete_student":
        $stmt = $db->prepare("delete from students where id = :id");
        $stmt->bindParam("id", $_POST["id"]);
        $stmt->execute();

        header("Content-Type: application/json");
        echo json_encode(["message" => "Student deleted successfully"]);
        break;

    case "get_student":
        $data = [];

        $stmt = $db->prepare("select * from students where id = :id limit 1");
        $stmt->bindParam("id", $_GET["id"]);
        $stmt->execute();
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("select courses.id from student_courses inner join courses on courses.id = student_courses.course_id where student_courses.student_id = ". $student["id"]);
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $course_ids = [];

        foreach ($courses as $course) 
        {
            array_push($course_ids, $course["id"]);
        }

        header("Content-Type: application/json");
        echo json_encode([
            "id" => (int)$student["id"],
            "name" => $student["name"],
            "email" => $student["email"],
            "phone" => (int)$student["phone"],
            "gender" => $student["gender"],
            "image_url" => $student["image_url"],
            "address" => $student["address"],
            "is_active" => $student["is_active"] == "1",
            "course_ids" => $course_ids
        ]);
        break;

    case "get_courses":
        $stmt = $db->prepare("select * from courses");
        $stmt->execute();
        header("Content-Type: application/json");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    default:
        http_response_code(404);
        header("Content-Type: application/json");
        echo json_encode(["error" => "Action not found!"]);
}
