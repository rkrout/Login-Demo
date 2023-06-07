<?php

require("db.php");

$stmt = $pdo->prepare("SELECT users.name AS user_name, users.email AS user_email, timings.* FROM timings INNER JOIN users ON users.id = timings.user_id");
$stmt->execute();
$timings = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require("header.php") ?>

<a href="/timing/create-timing.php" class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 
focus:ring-1 focus:ring-orange-600 focus:ring-offset-1 mb-3">Create New</a>

<table class="w-full border border-gray-300 rounded max-w-7xl mx-auto my-5">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <!-- <th class="p-2">Date</th> -->
            <th class="p-2">Punch In</th>
            <th class="p-2">Punch Out</th>
            <th class="p-2">Total Break Time</th>
            <th class="p-2">Overtime</th>
            <th class="p-2">Action</th>
        </tr>
    </thead>
    <tbody class="text-center">
        <?php if(count($timings) == 0): ?>
            <tr>
                <td colspan="7">No Records Found</td>
            </tr>
        <?php endif; ?>

        <?php foreach($timings as $timing): ?>
            <tr class="border-t border-t-gray-300">
                <td class="p-2"><?= $timing["user_name"] ?></td>
                <td class="p-2"><?= $timing["user_email"] ?></td>
                <!-- <td class="p-2"><?= date("d-m-Y", strtotime($timing["punch_in_time"])) ?></td> -->
                <td class="p-2"><?= date("d-m-Y h:i A", strtotime($timing["punch_in_time"])) ?></td>
                <td class="p-2"><?= date("d-m-Y h:i A", strtotime($timing["punch_out_time"])) ?></td>
                <td class="p-2"><?= intdiv($timing["total_break_time"], 60) . ":" . $timing["total_break_time"] % 60 . "" ?></td>
                <td class="p-2"><?= intdiv($timing["overtime"], 60) . ":" . $timing["overtime"] % 60 . ""?></td>
                <td class="p-2">
                    <a href="/timing/edit-timing.php?id=<?= $timing["id"] ?>" class="px-2 py-1 bg-yellow-600 rounded bg-yello-600 text-white 
                    focus:ring-offset-1 focus:ring-yellow-600 transition-all duration-300 hover:bg-yellow-800">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require("footer.php") ?>