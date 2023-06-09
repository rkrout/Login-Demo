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
$data = [];

if(isset($_GET["from_date"]) && isset($_GET["to_date"])) 
{
    $sql .= " WHERE DATE(date) >= :from_date AND DATE(date) <= :to_date";
    $data["from_date"] = $_GET["from_date"];
    $data["to_date"] = $_GET["to_date"];
}

$timings = find_all($sql, $data);

?>

<?php require("header.php") ?>

<form class="flex flex-col md:flex-row items-start md:items-center gap-4 md:gap-2 mb-2">
    <div class="flex items-center gap-2">
        From: <input type="date" name="from_date" id="from_date" class="form-control max-w-[200px]" value="<?= $_GET["from_date"] ?? "" ?>">

        To: <input type="date" name="to_date" id="to_date" class="form-control max-w-[200px]"value="<?= $_GET["to_date"] ?? "" ?>">
    </div>

    <div class="flex items-center gap-2">
        <button type="submit" class="btn btn-purple">Search</button>

        <button type="reset" type="submit" class="btn-clear btn btn-gray">Clear</button>

        <button type="button" class="btn-download btn btn-gray">Download Summary</button>

        <a href="/timing/create-timing.php" class="btn btn-primary">Create New</a>
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
        $(".btn-clear").click(function(){
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

        $(".btn-download").click(function(){
            window.location.href = `/timing/report.php?from_date=${$("input[name=from_date]").val()}&&to_date=${$("input[name=to_date]").val()}`
        })
    });
</script>

<?php require("footer.php") ?>

