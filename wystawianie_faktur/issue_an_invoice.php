<?php

require_once('TCPDF/tcpdf.php');
require_once('TCPDF/config/tcpdf_config.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 048', PDF_HEADER_STRING1 . "" . "\n" . PDF_HEADER_STRING2 . "");

$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__) . '/lang/pol.php')) {
    require_once(dirname(__FILE__) . '/lang/pol.php');
    $pdf->setLanguageArray($l);
}

$pdf->SetFont('helvetica', 'B', 20);

$pdf->AddPage();

$pdf->SetFont('helvetica', '', 8);

// -----------------------------------------------------------------------------

$tbl = <<<EOD
<br />
<br />
<table cellspacing="15" cellpadding="1" border="0">
    <tr>
        <td style="background-color:#FFFF00;color:#0000FF;" >Wystawca:</td>
        <td style="background-color:#FFFF00;color:#0000FF;" >Nabywca:</td>
    </tr>
    <tr>
        <td> <br />text line<br />text line<br />text line<br />text line COL 3 - ROW 2</td>
        <td> <br />text line<br />text line<br />text line<br />text line COL 3 - ROW 2</td>
    </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
$abc = "zmienny teskt";
// Table with rowspans and THEAD
$tbl = <<<EOD
<table border="1" cellpadding="2" cellspacing="0">
    <thead>
        <tr style="background-color:#FFFF00;color:#0000FF;">
            <td width="20" align="center"><b>Lp.</b></td>
            <td width="200" align="center"><b>Pozycja</b></td>
            <td width="40" align="center"><b>PKWIU</b></td>
            <td width="70" align="center"> <b>Cena netto</b></td>
            <td width="30" align="center"><b>Ilosc</b></td>
            <td width="30" align="center"><b>jedn.</b></td>
            <td width="70" align="center"><b>Wart. netto</b></td>
            <td width="30" align="center"><b>Vat%</b></td>
            <td width="60" align="center"><b>Kwota VAT</b></td>
            <td width="70" align="center"><b>Wart. brutto</b></td>
        </tr>
    </thead>
    <tr>
        <td width="20" align="center">$abc</td>
        <td width="200" align="center">$abc</td>
        <td width="40" align="center">$abc</td>
        <td width="70" align="center">$abc</td>
        <td width="30" align="center">$abc</td>
        <td width="30" align="center">$abc</td>
        <td width="70" align="center">$abc</td>
        <td width="30" align="center">$abc</td>
        <td width="60" align="center">$abc</td>
        <td width="70" align="center">$abc</td>
    </tr>
    <tr align="left">
        <td width="390" align="center">$abc</td>
        <td width="70" align="center">$abc</td>
        <td width="30" align="center">$abc</td>
        <td width="60" align="center">$abc</td>
        <td width="70" align="center">$abc</td>
    </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl = <<<EOD
<br />
<br />
<table cellspacing="10" cellpadding="1" border="0">
    <tr style="background-color:#FFFF00;color:#0000FF;">
        <td style="background-color:#FFFFFF;color:#FFFFFF;"></td>
        <td align="left"><b>Do zaplaty</b></td>
        <td align="right">$abc</td>
    </tr>
    <tr>
        <td style="background-color:#FFFFFF;color:#FFFFFF;"></td>
        <td align="left">Slownie:<br />Sposob zaplaty:<br />Termin:<br />Rachunek:</td>
        <td align="right">$abc<br />$abc<br />$abc<br />$abc</td>
    </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->Output('example_048.pdf', 'I');
