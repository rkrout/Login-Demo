<?php
$start_date = $_POST["punch_in_time"];
$end_date = $_POST["punch_out_time"];

if(strtotime($start_date) > strtotime($end_date)) {
    die("<script>alert('Punch in date must be smaller than punch out date');window.location.reload()</script>");
}

$stmt = $pdo->prepare("DELETE FROM timings WHERE id = :id");
$stmt->bindParam("id", $_GET["id"]);
$stmt->execute();

if((new DateTime($start_date))->format('Y-m-d') == (new DateTime($end_date))->format('Y-m-d')) {
    $stmt = $pdo->prepare("
        INSERT INTO timings
        (punch_in_time, punch_out_time, user_id)
        VALUES 
        (:punch_in_time, :punch_out_time, :user_id)
    ");
    $stmt->bindParam("user_id", $_POST["user_id"]);
    $stmt->bindParam("punch_in_time", $_POST["punch_in_time"]);
    $stmt->bindParam("punch_out_time", $_POST["punch_out_time"]);
    $stmt->execute();

    die("<script>alert('Data edited successfully'); window.location.href='/timing/index.php'</script>");
}

$period = new DatePeriod(
     new DateTime($start_date),
     new DateInterval('P1D'),
     new DateTime($end_date),
     DatePeriod::EXCLUDE_START_DATE
);

$final_date_array = [];

array_push($final_date_array, [
    "punch_in_time" => (new DateTime($start_date))->format('Y-m-d H:i:s'),
    "punch_out_time" => (new DateTime($start_date))->setTime(11, 59, 59)->format('Y-m-d H:i:s')
]);

foreach ($period as $key => $value) {
    array_push($final_date_array, [
        "punch_in_time" => $value->setTime(12, 00, 00)->format('Y-m-d H:i:s'),
        "punch_out_time" => $value->setTime(11, 59, 59)->format('Y-m-d H:i:s')
    ]);
}

array_push($final_date_array, [
    "punch_in_time" => (new DateTime($end_date))->setTime(12, 00, 00)->format('Y-m-d H:i:s'),
    "punch_out_time" => (new DateTime($end_date))->format('Y-m-d H:i:s')
]);

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

die("<script>alert('Data edited successfully'); window.location.href='/timing/index.php'</script>");

?>