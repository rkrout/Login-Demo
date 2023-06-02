<?php 

require("db.php");
$stmt = $pdo->prepare("select * from users inner join timings on users.id = timings.user_id");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require("header.php") ?>
<table class="w-full border border-gray-300 rounded max-w-5xl mx-auto my-5">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2">Name</th>
            <th class="p-2">Email</th>
            <th class="p-2">Date</th>
            <th class="p-2">Punch In</th>
            <th class="p-2">Punch Out</th>
            <th class="p-2">Overtime</th>
            <th class="p-2">Regular Time</th>
        </tr>
    </thead>
    <tbody class="text-center">
        <?php foreach($users as $user): ?>
        <tr class="border-t border-t-gray-300">
            <td class="p-2"><?= $user["name"] ?></td>
            <td class="p-2"><?= $user["email"] ?></td>
            <td class="p-2"><?= date("d-m-Y", strtotime($user["punch_in_time"])) ?></td>
            <td class="p-2"><?= date("H:i", strtotime($user["punch_in_time"])) ?></td>
            <td class="p-2"><?= date("H:i", strtotime($user["punch_out_time"])) ?></td>
            <td class="p-2"><?= intdiv($user["overtime"], 3600) . "hr " . intdiv($user["overtime"] % 3600, 60) . "min"?></td>
            <td class="p-2"><?= $user["regular_time"] ?>hr</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require("footer.php") ?>