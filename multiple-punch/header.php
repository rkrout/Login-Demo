<?php $file_name = explode(".", basename($_SERVER["PHP_SELF"]))[0]; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : "Multiple punching" ?></title>

    <!-- Jquery cdn  -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    
    <!-- Google icons cdn  -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    
    <!-- Tailwind css -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <nav class="bg-orange-600 h-16 shadow-lg">
        <div class="max-w-5xl mx-auto h-full flex items-center justify-between">
            <a href="/multiple-punch/index.php" class="text-2xl text-white font-bold">Employee Management</a>

            <ul class="flex gap-8 text-white">
                <li>
                    <a href="/multiple-punch/index.php" class="<?= in_array($file_name, ["index"]) ? "text-white font-bold" : "text-gray-100" ?>">Home</a>
                </li>
                <li>
                    <a href="/multiple-punch/punch.php" class="<?= in_array($file_name, ["punch"]) ? "text-white font-bold" : "text-gray-100" ?>">Punch</a>
                </li>
                <li>
                    <a href="/multiple-punch/user-schedules.php" class="<?= in_array($file_name, ["user-schedules", "edit-user-schedule"]) ? "text-white font-bold" : "text-gray-100" ?>">User Schedules</a>
                </li>
                <li>
                    <a href="/multiple-punch/schedules.php" class="<?= in_array($file_name, ["schedules", "edit-schedule", "create-schedule"]) ? "text-white font-bold" : "text-gray-100" ?>">Schedules</a>
                </li>
                <li>
                    <a href="/multiple-punch/settings.php" class="<?= in_array($file_name, ["settings", "edit-settings"]) ? "text-white font-bold" : "text-gray-100" ?>">Settings</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="max-w-8xl my-5 mx-auto px-3">

   
  
