<?php

session_start();

if(isset($_SESSION["username"]))
{
    header("Location: /gaming/config");
    die();
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $auth = json_decode(file_get_contents("auth.json"), true) ?? [];

    if($auth["username"] == $_POST["username"] && $auth["password"] == $_POST["password"])
    {
        $_SESSION["username"] = $auth["username"];
        header("Location: /gaming/config");
        die();
    }
    else 
    {
        $is_invalid_login = true;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <form method="post" class="card mx-auto" style="max-width: 500px">
            <div class="card-header fw-bold text-primary">Login</div>

            <div class="card-body">
                <?php if(isset($is_invalid_login)): ?>
                    <div class="alert alert-danger">Invalid email or password</div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required>
                </div>
                <button class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.js"></script>

</html>