<?php

require("db.php");

$sql = "
SELECT 
    users.name AS user_name, 
    users.email AS user_email, 
    timings.* 
FROM timings 
INNER JOIN users ON users.id = timings.user_id
";

if(isset($_GET["from_date"]) && isset($_GET["from_date"])) {
    $sql .= " WHERE DATE(punch_in_time) >= :from_date AND DATE(punch_out_time) <= :to_date";
}

$stmt = $pdo->prepare($sql);

if(isset($_GET["from_date"]) && isset($_GET["from_date"])) {
    $stmt->bindParam("from_date", $_GET["from_date"]);
    $stmt->bindParam("to_date", $_GET["to_date"]);
}

$stmt->execute();
$timings = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require("header.php") ?>

<form class="flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-2 mb-2">
    <div class="flex items-center gap-2">
        From: <input type="date" name="from_date" id="from_date" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" style="max-width: 200px" value="<?= $_GET["from_date"] ?? "" ?>">

        To: <input type="date" name="to_date" id="to_date" class="border border-gray-300 rounded px-4 py-2 w-full focus:ring-orange-600
        focus:ring-1 focus:border-orange-600 outline-none" style="max-width: 200px" value="<?= $_GET["to_date"] ?? "" ?>">
    </div>

    <div class="flex items-center gap-2">
        <button type="submit" class="px-4 py-2 rounded-md bg-purple-600 text-white hover:bg-purple-800 disabled:bg-purple-400 focus:ring-1 
        focus:ring-purple-600 focus:ring-offset-1">Search</button>

        <button type="reset" type="submit" class="btn-reset px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-800 disabled:bg-gray-400 focus:ring-1 
        focus:ring-gray-600 focus:ring-offset-1">clear</button>

        <a href="/timing/create-timing.php" class="px-4 py-2 rounded-md bg-orange-600 text-white hover:bg-orange-800 disabled:bg-orange-400 
        focus:ring-1 focus:ring-orange-600 focus:ring-offset-1">Create New</a>
    </div>
</form>

<div class="overflow-auto">
    <table class="w-full border border-gray-300 rounded max-w-8xl mx-auto mb-5 compact" style="min-width: 1024px" id="dataTable">
        <thead class="bg-gray-100">
            <tr>
                <th class="py-3 px-2">Name</th>
                <th class="py-3 px-2">Email</th>
                <th class="py-3 px-2">Date</th>
                <th class="py-3 px-2">Punch In</th>
                <th class="py-3 px-2">Punch Out</th>
                <th class="py-3 px-2">Total Break Time</th>
                <th class="py-3 px-2">Overtime</th>
                <th class="py-3 px-2">Double Time</th>
                <th class="py-3 px-2">Total Working Time</th>
                <th class="py-3 px-2">Action</th>
            </tr>
        </thead>
        <tbody class="text-center">
            <?php foreach($timings as $timing): ?>
                <tr class="border-t border-t-gray-300">
                    <td class="py-3 px-2"><?= $timing["user_name"] ?></td>
                    <td class="py-3 px-2"><?= $timing["user_email"] ?></td>
                    <td class="py-3 px-2"><?= date("d-m-Y h:i A", strtotime($timing["punch_in_time"])) ?></td>
                    <td class="py-3 px-2"><?= date("d-m-Y h:i A", strtotime($timing["punch_in_time"])) ?></td>
                    <td class="py-3 px-2"><?= date("d-m-Y h:i A", strtotime($timing["punch_out_time"])) ?></td>
                    <td class="py-3 px-2"><?= intdiv($timing["total_break_time"], 60) . ":" . $timing["total_break_time"] % 60 . "" ?></td>
                    <td class="py-3 px-2"><?= intdiv($timing["overtime"], 60) . ":" . $timing["overtime"] % 60 . ""?></td>
                    <td class="py-3 px-2"><?= intdiv($timing["double_time"], 60) . ":" . $timing["double_time"] % 60 ?></td>
                    <td class="py-3 px-2">12:23:00</td>
                    <td class="py-3 px-2">
                        <a href="/timing/edit-timing.php?id=<?= $timing["id"] ?>" class="px-2 py-1 bg-yellow-600 rounded bg-yello-600 text-white 
                        focus:ring-offset-1 focus:ring-yellow-600 transition-all duration-300 hover:bg-yellow-800">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $(".btn-reset").click(function(){
            window.location.href = "/timing/index.php"
        })

        $("#dataTable").DataTable({
            dom: "Bfrtip",
            pageLength:10,
            // processing: true,
            // serverSide: true,
            // ajax: {
            //     url:  "/timing/ajax.php?action=get_timing_records&&punch_in_time=<?= $_GET["from_date"] ?? "" ?>&&punch_out_time=<?= $_GET["to_date"] ?? "" ?>",
            // },
            buttons: [
                "copy", "csv", "excel", "pdf", "print"
            ]
        });

        if($("span .paginate_button").length == 1) {
            $("span .paginate_button").hide()
        } 
    });
</script>

<?php require("footer.php") ?>

