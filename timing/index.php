<?php

require("db-utils.php");
require("date-utils.php");

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

<div class="table-responsive">
    <table class="table min-w-[1024px]" id="dataTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Punch In</th>
                <th>Punch Out</th>
                <th>Break Time</th>
                <th>Overtime</th>
                <th>Double Time</th>
                <th>Working Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($timings as $timing): ?>
                <tr>
                    <td><?= $timing["user_name"] ?></td>

                    <td><?= $timing["user_email"] ?></td>

                    <td><?= date("d-m-Y", strtotime($timing["date"])) ?></td>

                    <td><?= date("d-m-Y h:i A", strtotime($timing["punch_in_time"])) ?></td>

                    <td><?= date("d-m-Y h:i A", strtotime($timing["punch_out_time"])) ?></td>

                    <td><?= get_sec_to_time($timing["break_time"]) ?></td>

                    <td><?= get_sec_to_time($timing["over_time"]) ?></td>

                    <td><?= get_sec_to_time($timing["double_time"]) ?></td>

                    <td><?= get_sec_to_time($timing["working_time"]) ?></td>

                    <td>
                        <a href="/timing/edit-timing.php?id=<?= $timing["id"] ?>" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $(".btn-reset").click(function(){
            window.location.href = "/timing/index.php"
        })

        $("#dataTable").DataTable({
            dom: "Bfrtip",
            pageLength:10,
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

