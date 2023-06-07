<?php

require("db.php");

$stmt = $pdo->prepare("SELECT * FROM settings LIMIT 1");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<?php require("header.php") ?>

<table class="w-full border border-gray-300 rounded max-w-7xl mx-auto my-5">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2">Regular Time</th>
            <th class="p-2">Break Time</th>
            <th class="p-2">Break Interval</th>
            <th class="p-2">Split Punch</th>
            <th class="p-2">Double Time</th>
            <th class="p-2">Action</th>
        </tr>
    </thead>
    <tbody class="text-center">
        <tr class="border-t border-t-gray-300">
            <td class="p-2"><?= $settings["regular_time"] ?>hr</td>
            <td class="p-2"><?= $settings["break_time"] ?>min</td>
            <td class="p-2"><?= $settings["break_interval"] ?>hr</td>
            <td>
                <?php if($settings["is_split_punch"]): ?>
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                <?php else: ?>
                    <span class="material-symbols-outlined text-red-600">cancel</span>
                <?php endif; ?>
            </td>
            <td class="p-2"><?= $settings["double_time"] ?>hr</td>
            <td class="p-2">
                <a href="/timing/edit-settings.php" class="px-2 py-1 bg-yellow-600 rounded bg-yello-600 text-white 
                focus:ring-offset-1 focus:ring-yellow-600 transition-all duration-300 hover:bg-yellow-800">Edit</a>
            </td>
        </tr>
    </tbody>
</table>

<?php require("footer.php") ?>