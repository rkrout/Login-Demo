<?php
$start_date = $_POST["punch_in_time"];
$end_date = $_POST["punch_out_time"];

if($end_date && strtotime($start_date) > strtotime($end_date)) {
    die("<script>alert('Punch in date must be smaller than punch out date');window.location.href='/timing/index.php'</script>");
}

if(isset($_GET["group_id"])) {
    $stmt = $pdo->prepare("DELETE FROM timings WHERE group_id = :group_id");
    $stmt->bindParam("group_id", $_GET["group_id"]);
    $stmt->execute();
}

$stmt = $pdo->prepare("SELECT * FROM settings");
$stmt->execute();
$setting = $stmt->fetch(PDO::FETCH_ASSOC);

if((new DateTime($start_date))->format('Y-m-d') == (new DateTime($end_date))->format('Y-m-d') || !$setting["is_split_punch"] || !$end_date) {
    $stmt = $pdo->prepare("
        INSERT INTO timings
        (punch_in_time, punch_out_time, user_id, group_id)
        VALUES 
        (:punch_in_time, :punch_out_time, :user_id, group_id)
    ");
    $stmt->bindParam("user_id", $_POST["user_id"]);
    $stmt->bindParam("punch_in_time", $_POST["punch_in_time"]);
    $stmt->bindValue("punch_out_time", $_POST["punch_out_time"] ?? NULL);
    $stmt->bindValue("group_id", bin2hex(random_bytes(20)));
    $stmt->execute();

    require("edit-timings.php");

    die("<script>alert('Data edited successfully'); window.location.href='/timing/index.php'</script>");
}

require("date-utils.php");

$date_range = createDateRangeArray($start_date, $end_date);

$final_date_range = [];

for($i = 0; $i < count($date_range); $i++) {
    if($i == 0) {
        array_push($final_date_range, [
            "punch_in_time" => $date_range[$i] . " " . date("H:i:s", strtotime($start_date)),
            "punch_out_time" => $date_range[$i] . " 23:59:59"
        ]); 
    }else if($i == count($date_range) - 1){
        array_push($final_date_range, [
            "punch_in_time" => $date_range[$i] . " 00:00:00",
            "punch_out_time" => $date_range[$i] . " " . date("H:i:s", strtotime($end_date))
        ]); 
    } else {
        array_push($final_date_range, [
            "punch_in_time" => $date_range[$i] . " 00:00:00",
            "punch_out_time" => $date_range[$i] . " 23:59:59"
        ]); 
    } 
}

// echo "<pre>";
// echo print_r($final_date_range);
// echo "</pre>";
// die;
$group_id = bin2hex(random_bytes(30));

foreach ($final_date_range as $date) {
    $stmt = $pdo->prepare("
        INSERT INTO timings
        (punch_in_time, punch_out_time, user_id)
        VALUES 
        (:punch_in_time, :punch_out_time, :user_id)
    ");
    $stmt->bindParam("user_id", $_POST["user_id"]);
    $stmt->bindParam("punch_in_time", $date["punch_in_time"]);
    $stmt->bindParam("punch_out_time", $date["punch_out_time"]);
    $stmt->bindParam("group_id", $group_id);
    $stmt->execute();
}

require("edit-timings.php");

die("<script>alert('Data edited successfully'); window.location.href='/timing/index.php'</script>");

?>