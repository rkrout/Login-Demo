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

$total_break_time = get_sec_to_time($total_break_time, false);
$total_over_time = get_sec_to_time($total_over_time, false);
$total_double_time = get_sec_to_time($total_double_time, false);
$total_working_time = get_sec_to_time($total_working_time, false);

function get_table_rows()
{
    $table_rows = "";

    global $timings;

    foreach($timings as $timing)
    {
        $table_rows .= "
            <tr>
                <td>". date("m-d-Y", strtotime($timing['date'])) ."</td>

                <td>". 8 ."</td>

                <td>". get_sec_to_time($timing['working_time'], false) ."</td>

                <td>". get_sec_to_time($timing['break_time'], false) ."</td>

                <td>". get_sec_to_time($timing['over_time'], false) ."</td>

                <td>". get_sec_to_time($timing['double_time'], false) ."</td>
            </tr>
        ";
    }

    return $table_rows;
}

require_once "../vendor/autoload.php";

$mpdf = new \Mpdf\Mpdf([
    "default_font_size" => 10,
	// "default_font" => "times"
	// "default_font" => "Serif"
	// "default_font" => "frutiger"
	// "default_font" => "Arial",
	"default_font" => "Calibri",
]);
$mpdf->AddPageByArray([
    'margin-left' => 8,
    'margin-right' => 8,
    'margin-top' => 8,
    'margin-bottom' => 8,
]);
$mpdf->WriteHTML("
    <html>
    <head>
        <style>
            p{
                margin-bottom: 8px;
                margin-top: 0px;
            }

            .header-1{
                border-bottom: 1px dashed #4b5563;
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
                border-bottom: 1px dashed #4b5563;
                margin-top: 8px;
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
                border-bottom: 1px dashed #4b5563;
                margin-bottom: 8px;
                padding-bottom: 8px;
            }

            .summary-text-div {
                margin-bottom: 8px;
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
                margin: 8px 0px;
            }

            table td {
                padding: 4px 0px;
            }

            thead td {
                border-bottom: 1px dashed #4b5563;
            }

            .page {
                border: 1.5px solid #4b5563; 
                padding: 12px;
                height: 100%;
            }
        </style>
    </head>
    <body>
        <div class='page'>
            <div class='header-1'>
                <div class='header-1-left'>
                    <p>Bluesummit</p>
                    <p>Dt - 09/09/2033</p>
                </div>
                <div class='header-1-middle'>
                    <p>
                        <b>BST TIMEKEEPER</b>
                    </p>
                    <p>Pay Period Report</p>
                </div>
            </div>

            <div class='header-2'>
                <div class='header-2-left'>
                    <p>Frequency : Monthly</p>
                    <p>Dt Range : 03/02/2023 - 08/03/2023
                </div>
                <div class='header-2-middle'>
                    <p>Emp Name : John Doe</p>
                    <p>Badge No : 4567</p>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <td style='padding-bottom:8px;' width='15%'>Date</td>
                        <td style='padding-bottom:8px;' width='25%'>Regular working hrs</td>
                        <td style='padding-bottom:8px;' width='15%'>Actual worked</td>
                        <td style='padding-bottom:8px;' width='15%'>Break time</td>
                        <td style='padding-bottom:8px;' width='15%'>Over time</td>
                        <td style='padding-bottom:8px;' width='15%'>Double time</td>
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
        </div>
    </body>
    </html>
");
$mpdf->Output();