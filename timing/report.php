<?php 

require_once("db-utils.php");
require_once("date-utils.php");

$sql = "
    SELECT 
        users.name AS user_name, 
        timings.* 
    FROM timings 
    INNER JOIN users ON users.id = timings.user_id
    WHERE DATE(date) >= :from_date AND DATE(date) <= :to_date
";

$timings = find_all($sql, [
    "from_date" => $_GET["from_date"],
    "to_date" => $_GET["to_date"]
]);

$total_break_time = 0;
$total_over_time = 0;
$total_double_time = 0;
$total_working_time = 0;

foreach ($timings as $timing) 
{
    $total_break_time += $timing["break_time"];
    $total_over_time += $timing["over_time"];
    $total_double_time += $timing["double_time"];
    $total_working_time += $timing["working_time"];
}

$total_break_time = get_sec_to_time($total_break_time);
$total_over_time = get_sec_to_time($total_over_time);
$total_double_time = get_sec_to_time($total_double_time);
$total_working_time = get_sec_to_time($total_working_time);

function get_table_rows()
{
    $table_rows = "";

    global $timings;

    foreach($timings as $timing)
    {
        $table_rows .= "
            <tr>
                <td>". date('d-m-Y', strtotime($timing['date'])) ."</td>

                <td>". get_sec_to_time($timing['break_time']) ."</td>

                <td>". get_sec_to_time($timing['over_time']) ."</td>

                <td>". get_sec_to_time($timing['double_time']) ."</td>

                <td>". get_sec_to_time($timing['working_time']) ."</td>
            </tr>
        ";
    }

    return $table_rows;
}

// require '../vendor/autoload.php';
// use Dompdf\Dompdf;

// // instantiate and use the dompdf class
// $dompdf = new Dompdf();
// $dompdf->loadHtml("<html><div style='display: flex; justify-content: space-between;'><p style='float: right; border-bottom: 1px dotted black'>hello</p> <p>dd</p></div></html>");

// // (Optional) Setup the paper size and orientation
// $dompdf->setPaper('A4', 'landscape');

// // Render the HTML as PDF
// $dompdf->render();

// // Output the generated PDF to Browser
// $dompdf->stream();









require_once "../vendor/autoload.php";

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML("
    <html>
    <head>
        <style>
            .header-1{
                border-bottom: 1px dashed black;
            }

            .header-1-left{
                float:left;
                width:33.3%;
            }

            .header-1-middle{
                float:left;
                width:33.3%;
                text-align: center;
            }

            .header-2{
                border-bottom: 1px dashed black;
            }

            .header-2-left{
                float:left;
                width:50%;
            }
            
            .header-2-middle{
                float:left;
                width:50%;
            }

            .summary-text {
                width: 40%;
            }

            .summary-text-heading {
                border-bottom: 1px dashed black;
                margin-bottom: 12px;
                padding-bottom: 8px;
            }

            .summary-text-div {
                margin-bottom: 12px;
            }

            .summary-text-left {
                float: left;
                width: 78%;
            }

            .summary-text-right {
                float: right;
                width: 20%;
            }

            table{
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0px;
            }

            table th,
            table td {
                padding: 8px 4px;
            }

            thead td {
                border-bottom: 1px dashed black;
            }
        </style>
    </head>
    <body>
        <div class='header-1'>
            <div class='header-1-left'>
                <p>Bluesummit</p>
                <p>Dt - 09/09/2033</p>
            </div>
            <div class='header-1-middle'>
                <p>Bst Timekeeper</p>
                <p>PAY PEROD REPORT</p>
            </div>
        </div>

        <div class='header-2'>
            <div class='header-2-left'>
                <p>Frequency : Monthly</p>
                <p>Dt Range : 01/03/2022 - 07/08/2023
            </div>
            <div class='header-2-middle'>
                <p>Emp No : 5677</p>
                <p>Badge No : 5678/2023
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <td>Date</td>
                    <td>Break Time</td>
                    <td>Over Time</td>
                    <td>Double Time</td>
                    <td>Working Time</td>
                </tr>
            </thead>
            <tbody>".
                get_table_rows()
            ."</tbody>
        </table>
    
        <div class='summary-text'>
            <div class='summary-text-heading'>SUMMARY</div>
            <div class='summary-text-div'>
                <div class='summary-text-left'>Total break time</div>
                <div class='summary-text-right'>$total_break_time</div>
            </div>
            <div class='summary-text-div'>
                <div class='summary-text-left'>Total over time</div>
                <div class='summary-text-right'>$total_over_time</div>
            </div>
            <div class='summary-text-div'>
                <div class='summary-text-left'>Total working time</div>
                <div class='summary-text-right'>$total_working_time</div>
            </div>
            <div class='summary-text-div'>
                <div class='summary-text-left'>Total double time</div>
                <div class='summary-text-right'>$total_double_time</div>
            </div>
        </div>
    </body>
    </html>
");
$mpdf->Output();