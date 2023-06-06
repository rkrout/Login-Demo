<?php
$start_date = "2010-10-01 10:00:00";
$end_date = "2010-10-02 9:00:00";

if(strtotime($start_date) > strtotime($end_date)) {
    // invalid time
} else {
    echo "valid";
}

if((new DateTime($start_date))->format('Y-m-d') == (new DateTime($end_date))->format('Y-m-d')) {
    // same day
} else {
    echo 'not same day';
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

print_r($final_date_array);

?>