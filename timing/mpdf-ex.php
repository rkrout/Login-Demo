<?php
require_once("../vendor/autoload.php");
$mpdf = new \Mpdf\Mpdf();

$html = '
<html>
<head>
<style>
    @page {
      size: auto;
      odd-header-name: html_MyHeader1;
      odd-footer-name: html_MyFooter1;
    }

    @page chapter2 {
        odd-header-name: html_MyHeader2;
        odd-footer-name: html_MyFooter2;
    }

    @page noheader {
        odd-header-name: _blank;
        odd-footer-name: _blank;
    }

    div.chapter2 {
        page-break-before: always;
        page: chapter2;
    }

    div.noheader {
        page-break-before: always;
        page: noheader;
    }
</style>
</head>
<body>
    <htmlpageheader name="MyHeader1">
        <div style="text-align: right; border-bottom: 1px solid #000000; font-weight: bold; font-size: 10pt;">My document</div>
    </htmlpageheader>

    <htmlpageheader name="MyHeader2">
        <div style="border-bottom: 1px solid #000000; font-weight: bold;  font-size: 10pt;">My document</div>
    </htmlpageheader>

    <htmlpagefooter name="MyFooter1">
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>
                <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE j-m-Y}</span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; ">My document</td>
            </tr>
        </table>
    </htmlpagefooter>

    <htmlpagefooter name="MyFooter2">
        <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
            <tr>
                <td width="33%"><span style="font-weight: bold; font-style: italic;">My document</span></td>
                <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right; ">{DATE j-m-Y}</td>
            </tr>
        </table>
    </htmlpagefooter>

    <div>Here is the text of the first chapter</div>
    <div class="chapter2">Text of Chapter 2</div>

    <div class="noheader">No-Header page</div>
</body>
</html>';

$mpdf->WriteHTML($html);

$mpdf->Output();