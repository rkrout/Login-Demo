<?php 

require_once("auth.php");

function validate_create_form() 
{
    $errors = [];
    
    // validating name

    if(!isset($_POST["name"]))
    {
        $errors["name"] = "Name is required";
    }
    else 
    {
        $_POST["name"] = trim($_POST["name"]);

        if(empty($_POST["name"]))
        {
            $errors["name"] = "Name is required";
        }
    }

    // validating logo
    
    if(!isset($_FILES["logo"]))
    {
        $errors["logo"] = "Logo is required";
    }
    else if($_FILES["logo"]["size"] == 0)
    {
        $errors["logo"] = "Logo is required";
    }
    else if($_FILES["logo"]["error"] == 1)
    {
        $errors["logo"] = "There is a problem in uploading the logo";
    }
    else 
    {
        $extension = pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);

        $accepted = ["jpg", "jpeg", "png"];

        if(!in_array($extension, $accepted))
        {
            $errors["logo"] = "Only jpg, jpeg or png images are accepted";
        }
    }

    // validating link

    if(!isset($_POST["link"]))
    {
        $errors["link"] = "Link is required";
    }
    else 
    {
        $_POST["link"] = trim($_POST["link"]);

        if(empty($_POST["link"]))
        {
            $errors["link"] = "Link is required"; 
        }
        else if(!filter_var($_POST["link"], FILTER_VALIDATE_URL))
        {
            $errors["link"] = "Invalid link";
        }
    }

    // validating target

    if(!isset($_POST["target"]))
    {
        $errors["target"] = "Target is required";
    }
    else if(!in_array($_POST["target"], ["New Tab", "Same Page"]))
    {
        $errors["link"] = "Invalid value";
    }

    // validating show

    if(!isset($_POST["show"]))
    {
        $errors["show"] = "Invalid value";
    }
    else if(!in_array($_POST["show"], ["true", "false"]))
    {
        $errors["show"] = "Invalid value";
    }

    return $errors;
}

function create_game()
{
    $games = json_decode(file_get_contents("games.json"), true) ?? [];

    $games_count = count($games);

    $id = $games_count > 0 ? $games[$games_count - 1]["id"] + 1 : 1;

    $image_name = uniqid() . "." . pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);

    move_uploaded_file($_FILES["logo"]["tmp_name"], "uploads/$image_name");

    array_push($games, [
        "id" => $id,
        "name" => $_POST["name"],
        "logo_url" => $image_name,
        "link" => $_POST["link"],
        "target" => $_POST["target"],
        "show" => $_POST["show"] == "true"
    ]);

    file_put_contents("games.json", json_encode($games));
}

function validate_edit_form() 
{
    $errors = [];

    // validate id
    if(!isset($_POST["id"]))
    {
        $errors["id"] = "Invalid data";
    }
    
    // validating name

    if(!isset($_POST["name"]))
    {
        $errors["name"] = "Name is required";
    }
    else 
    {
        $_POST["name"] = trim($_POST["name"]);

        if(empty($_POST["name"]))
        {
            $errors["name"] = "Name is required";
        }
    }

    // validating logo
    
    if(isset($_FILES["logo"]) && $_FILES["logo"]["size"] > 0)
    {
        if($_FILES["logo"]["error"] == 1)
        {
            $errors["logo"] = "There is a problem in uploading the logo";
        }
        else 
        {
            $extension = pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);

            $accepted = ["jpg", "jpeg", "png"];

            if(!in_array($extension, $accepted))
            {
                $errors["logo"] = "Only jpg, jpeg or png images are accepted";
            }
        }
    }

    // validating link

    if(!isset($_POST["link"]))
    {
        $errors["link"] = "Link is required";
    }
    else 
    {
        $_POST["link"] = trim($_POST["link"]);

        if(empty($_POST["link"]))
        {
            $errors["link"] = "Link is required";
        }
        else if(!filter_var($_POST["link"], FILTER_VALIDATE_URL))
        {
            $errors["link"] = "Invalid link";
        }
    }

    // validating target

    if(!isset($_POST["target"]))
    {
        $errors["target"] = "Target is required";
    }
    else if(!in_array($_POST["target"], ["New Tab", "Same Page"]))
    {
        $errors["link"] = "Invalid value";
    }

    // validating show

    if(!isset($_POST["show"]))
    {
        $errors["show"] = "Invalid value";
    }
    else if(!in_array($_POST["show"], ["true", "false"]))
    {
        $errors["show"] = "Invalid value";
    }

    return $errors;
}

function edit_game() 
{
    $games = json_decode(file_get_contents("games.json"), true) ?? [];

    for($i = 0; $i < count($games); $i++)
    {
        if($games[$i]["id"] == $_POST["id"])
        {
            if(isset($_FILES["logo"]) && $_FILES["logo"]["size"] > 0)
            {
                $image_name = uniqid() . "." . pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
            
                move_uploaded_file($_FILES["logo"]["tmp_name"], "uploads/$image_name");
            }
            else 
            {
                $image_name = $games[$i]["logo_url"];
            }

            $games[$i] = [
                "id" => $_POST["id"],
                "name" => $_POST["name"],
                "logo_url" => $image_name,
                "link" => $_POST["link"],
                "target" => $_POST["target"],
                "show" => $_POST["show"] == "true"
            ];   
        }
    }

    file_put_contents("games.json", json_encode($games));
}

function validate_toggle_show_form() 
{
    $errors = [];

    // validate id
    if(!isset($_POST["id"]))
    {
        $errors["id"] = "Invalid data";
    }
    
    return $errors;
}

function toggle_show() 
{
    $games = json_decode(file_get_contents("games.json"), true) ?? [];

    for($i = 0; $i < count($games); $i++)
    {
        if($games[$i]["id"] == $_POST["id"])
        {
            $games[$i]["show"] = !$games[$i]["show"];
        }
    }

    file_put_contents("games.json", json_encode($games));
}

if(isset($_GET["action"]) && $_GET["action"] == "edit") 
{
    $errors = validate_edit_form();

    if(count($errors) > 0)
    {
        echo json_encode($errors);
        http_response_code(422);
    }
    else 
    {
        edit_game();
    }
}

else if(isset($_GET["action"]) && $_GET["action"] == "create")
{
    $errors = validate_create_form();

    if(count($errors) > 0)
    {
        echo json_encode($errors);
        http_response_code(422);
    }
    else 
    {
        create_game();
    }
} 

else if(isset($_GET["action"]) && $_GET["action"] == "toggle_show") 
{
    $errors = validate_toggle_show_form();

    if(count($errors) > 0)
    {
        echo json_encode($errors);
        http_response_code(422);
    }
    else 
    {
        toggle_show();
    }
}