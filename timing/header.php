<?php $file_name = explode(".", basename($_SERVER['PHP_SELF']))[0]; ?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : "Timing" ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
</head>

<body>
    <nav class="bg-orange-600 h-16 shadow-lg">
        <div class="max-w-5xl mx-auto h-full flex items-center justify-between">
            <a href="/timing/index.php" class="text-2xl text-white font-bold">Daily Timing</a>

            <ul class="flex gap-8 text-white">
                <li>
                    <a href="index.php" class="<?= in_array($file_name, ["index", "edit-timing"]) ? "text-white font-bold" : "text-gray-100" ?>">Home</a>
                </li>
                <li>
                    <a href="/timing/settings.php" class="<?= in_array($file_name, ["settings", "edit-settings"]) ? "text-white font-bold" : "text-gray-100" ?>">Setting</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="max-w-5xl my-5 mx-auto px-3">

   
  
