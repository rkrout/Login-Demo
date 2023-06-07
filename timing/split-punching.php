<?php
$start_date = $_POST["punch_in_time"];
$end_date = $_POST["punch_out_time"];

if($end_date && strtotime($start_date) > strtotime($end_date)) {
    die("<script>alert('Punch in date must be smaller than punch out date');window.location.href='/timing/index.php'</script>");
}

if(isset($_GET["id"])) {
    $stmt = $pdo->prepare("DELETE FROM timings WHERE id = :id");
    $stmt->bindParam("id", $_GET["id"]);
    $stmt->execute();
}

$stmt = $pdo->prepare("SELECT * FROM settings");
$stmt->execute();
$setting = $stmt->fetch(PDO::FETCH_ASSOC);

if((new DateTime($start_date))->format('Y-m-d') == (new DateTime($end_date))->format('Y-m-d') || !$setting["is_split_punch"] || !$end_date) {
    $stmt = $pdo->prepare("
        INSERT INTO timings
        (punch_in_time, punch_out_time, user_id)
        VALUES 
        (:punch_in_time, :punch_out_time, :user_id)
    ");
    $stmt->bindParam("user_id", $_POST["user_id"]);
    $stmt->bindParam("punch_in_time", $_POST["punch_in_time"]);
    $stmt->bindValue("punch_out_time", $_POST["punch_out_time"] ?? NULL);
    $stmt->execute();

    require("edit-timings.php");

    die("<script>alert('Data edited successfully dd'); window.location.href='/timing/index.php'</script>");
}

$period = new DatePeriod(
     new DateTime($start_date),
     new DateInterval('P1D'),
     new DateTime($end_date)
);

$final_date_array = [];

$period_arr = [];

foreach ($period as $key => $value) {
    array_push($period_arr, $value);
}

for($i=0; $i < count($period_arr); $i++) {
    if($i == 0) {
        array_push($final_date_array, [
            "punch_in_time" => $period_arr[$i]->format('Y-m-d H:i:s'),
            "punch_out_time" => $period_arr[$i]->setTime(23, 59, 59)->format('Y-m-d H:i:s')
        ]); 
    }else {
        array_push($final_date_array, [
            "punch_in_time" => $period_arr[$i]->setTime(00, 00, 00)->format('Y-m-d H:i:s'),
            "punch_out_time" => $period_arr[$i]->setTime(23, 59, 59)->format('Y-m-d H:i:s')
        ]);  
    }  
}

array_push($final_date_array, [
    "punch_in_time" => (new DateTime($end_date))->setTime(00, 00, 00)->format('Y-m-d H:i:s'),
    "punch_out_time" => (new DateTime($end_date))->format('Y-m-d H:i:s')
]);

// echo "<pre>";
// echo print_r($final_date_array);
// echo "</pre>";
// die;

foreach ($final_date_array as $date) {
    $stmt = $pdo->prepare("
        INSERT INTO timings
        (punch_in_time, punch_out_time, user_id)
        VALUES 
        (:punch_in_time, :punch_out_time, :user_id)
    ");
    $stmt->bindParam("user_id", $_POST["user_id"]);
    $stmt->bindParam("punch_in_time", $date["punch_in_time"]);
    $stmt->bindParam("punch_out_time", $date["punch_out_time"]);
    $stmt->execute();
}

require("edit-timings.php");

die("<script>alert('Data edited successfully'); window.location.href='/timing/index.php'</script>");

?>