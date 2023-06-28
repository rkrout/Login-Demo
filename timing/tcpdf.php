<?php 
require '../vendor/autoload.php';
require_once("db-utils.php");
require_once("date-utils.php");
require_once("../vendor/autoload.php");

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

$settings = find_one("SELECT * FROM settings LIMIT 1");

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

    global $timings, $settings;

    $index = 0;

    foreach($timings as $timing)
    {
        $table_rows .= "
            <tr>
                <td style='text-align: left'>". date("m-d-Y", strtotime($timing['date'])) ."</td>

                <td style='text-align: left'>". get_day_from_date($timing['date']) ."</td>

                <td>". get_sec_to_time($timing['working_time'], false) ."</td>

                <td>". get_sec_to_time($settings["regular_time"], false) ."</td>

                <td>". get_sec_to_time($timing['break_time'], false) ."</td>

                <td>". get_sec_to_time($timing['over_time'], false) ."</td>

                <td>". get_sec_to_time($timing['double_time'], false) ."</td>
            </tr>
        ";

        $index++;
    }

    return $table_rows;
}

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 001');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$pdf->SetLineStyle( array( 'width' => 15, 'color' => array(0,0,0)));

$pdf->Line(0,0,$pdf->getPageWidth(),0); 
$pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
$pdf->Line(0,0,0,$pdf->getPageHeight());

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = <<<EOD
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This is the first example of TCPDF library.</i>
<p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
<p>Please check the source code documentation and other examples for further information.</p>
<p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
EOD;

// Print text using writeHTMLCell()
// $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->writeHTML("
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
            border-collapse: collapse;
            text-align: right;
        }

        tbody td {
            padding-top: 8px;
        }

        thead td {
            border-bottom: 1px dashed #4b5563;
            padding-bottom: 8px;
        }

        .page {
            // border: 1.5px solid #4b5563; 
            padding: 12px;
            height: 100%;
            position: relative;
        }

        .logo {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body style='background: url('./images.jpg')'>

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
                    <td style='text-align: left'>Date</td>
                    <td style='text-align: left'>Day</td>
                    <td>Actual worked</td>
                    <td>Regular hrs</td>
                    <td>Break time</td>
                    <td>Over time</td>
                    <td>Double time</td>
                </tr>
            </thead>
            <tbody>
                ".get_table_rows()."
                ".get_table_rows()."
                <tr>
                    <td style='padding-top:8px' colspan='6'></td>
                </tr>
                <tr>
                    <td style='border-top: 1px solid #4b5563; margin-top: 80px; text-align: left;' colspan='2'>Summary</td>
                    <td style='border-top: 1px solid #4b5563; margin-top: 8px;'>$total_working_time</td>
                    <td style='border-top: 1px solid #4b5563; margin-top: 8px;'>5:00</td>
                    <td style='border-top: 1px solid #4b5563; margin-top: 8px;'>$total_break_time</td>
                    <td style='border-top: 1px solid #4b5563; margin-top: 8px;'>$total_over_time</td>
                    <td style='border-top: 1px solid #4b5563; margin-top: 8px;'>$total_double_time</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style='position: absolute; bottom: 8px; right: 32px; height: 40px; width: 100px;'>
        <img src='./logo-full.png' class='logo'>
    </div>
</body>
</html>
", true, false, true, false, '');
$pdf->Output('example_001.pdf', 'I');

