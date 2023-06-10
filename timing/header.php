<?php $file_name = explode(".", basename($_SERVER['PHP_SELF']))[0]; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : "Timing" ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        .dt-buttons {
            display: flex;
            gap: 4px;
            margin: 12px 0px;
            margin-bottom: -40px;
        }
        .dt-button {
            padding: 6px 12px;
            border-radius: 6px;
            background-color: #ccc;
        }
        .dataTables_filter {
            float: right;
            display: inline-block;
            margin-bottom: 24px;
        }
        .dataTables_filter input{
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 8px;
            margin-left: 12px;
        }
        @media (max-width: 768px) {
            .dataTables_filter{
                float: none;
            }
            .dt-buttons{
                margin-bottom: 12px;
            }
        }
        .paginate_button.current{
            border: 1px solid #444 !important;
        }
        .paginate_button.disabled{
            display: none;
        }
        .paginate_button {
            padding: 4px 8px;
            border-radius: 4px;
            background-color: #ccc;
            cursor: pointer;
            margin-right: 4px;
        }
        .paginate_button:last-child{
            margin-right: 0px;
        }
        .dataTables_paginate.paging_simple_numbers {
            display: flex;
            align-items: center;
            gap: 4px;
            float: right;
            margin-top: -20px;
        }
    </style>
</head>

<body>
    <nav class="bg-orange-600 h-16 shadow-lg">
        <div class="max-w-5xl mx-auto h-full flex items-center justify-between">
            <a href="/timing/index.php" class="text-2xl text-white font-bold">Daily Timing</a>

            <ul class="flex gap-8 text-white">
                <li>
                    <a href="index.php" class="<?= in_array($file_name, ["index", "edit-timing", "create-timing"]) ? "text-white font-bold" : "text-gray-100" ?>">Home</a>
                </li>
                <li>
                    <a href="/timing/settings.php" class="<?= in_array($file_name, ["settings", "edit-settings"]) ? "text-white font-bold" : "text-gray-100" ?>">Setting</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="max-w-8xl my-5 mx-auto px-3">

   
  
