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

$sql .= "LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

if(isset($_GET["from_date"]) && isset($_GET["from_date"])) {
    $stmt->bindParam("from_date", $_GET["from_date"]);
    $stmt->bindParam("to_date", $_GET["to_date"]);
}

$stmt->bindParam("offset", $_GET["start"]);
$stmt->bindParam("limit", $_GET["length"]);

$stmt->execute();
$timings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$final_data = [];

foreach ($timings as $timing) {
    array_push($final_data, [
        $timing["user_name"],
        $timing["user_email"],
        date("d-m-Y h:i A", strtotime($timing["punch_in_time"])),
        date("d-m-Y h:i A", strtotime($timing["punch_out_time"])),
        intdiv($timing["total_break_time"], 60) . ":" . $timing["total_break_time"] % 60 . "",
        intdiv($timing["overtime"], 60) . ":" . $timing["overtime"] % 60 . "",
        "<a href='/timing/edit-timing.php?id='". $timing["id"] ." class='px-2 py-1 bg-yellow-600 rounded bg-yello-600 text-white 
        focus:ring-offset-1 focus:ring-yellow-600 transition-all duration-300 hover:bg-yellow-800'>Edit</a>"
    ]);
}

$stmt = $pdo->prepare("select * from timings");
$stmt->execute();
$users = $stmt->fetchAll();
$total_timings = count($users);

echo json_encode([
    "draw" => rand(1, 999),
    "recordsTotal" => $total_timings,
    "recordsFiltered" => count($final_data),
    "data" => $final_data
]);