<?php

require_once('TCPDF/tcpdf.php');
require_once('TCPDF/config/tcpdf_config.php');


include 'cors_headers.php';
include 'send_email.php';

$invoices_json_path = 'invoices.json';
if (file_exists(dirname(__FILE__) . $invoices_json_path))
    return "Orders JSON does not exist";
$invoices_json = json_decode(file_get_contents($invoices_json_path), true);

$company_json_path = 'company_info.json';
if (file_exists(dirname(__FILE__) . $company_json_path))
    return "Orders JSON does not exist";
$company_json = json_decode(file_get_contents($company_json_path), true);

$todays_date = date("Y/m/d");

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' ' . $invoices_json[0]["identifier"] . "/" . $todays_date, PDF_HEADER_STRING1 . $todays_date . "\n" . PDF_HEADER_STRING2 . $todays_date);

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
$company_name = $company_json["name"];
$company_address1 = $company_json["address1"];
$company_address2 = $company_json["address2"];
$company_nip = $company_json["nip"];
$client_name = $invoices_json[0]["reciever"]["name"];
$client_address1 = $invoices_json[0]["reciever"]["address1"];
$client_address2 = $invoices_json[0]["reciever"]["address2"];
$client_nip = $invoices_json[0]["reciever"]["nip"];
$tbl = <<<EOD
<br />
<br />
<table cellspacing="15" cellpadding="1" border="0">
    <tr>
        <td style="background-color:#FFFF00;color:#0000FF;" >Wystawca:</td>
        <td style="background-color:#FFFF00;color:#0000FF;" >Nabywca:</td>
    </tr>
    <tr>
        <td> <br />$company_name<br />$company_address1<br />$company_address2<br />$company_nip</td>
        <td> <br />$client_name<br />$client_address1<br />$client_address2<br />$client_nip</td>
    </tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');
$lp = $invoices_json[0]["products"][0]["lp"];
$name = $invoices_json[0]["products"][0]["name"];
$pkwiu = $invoices_json[0]["products"][0]["pkwiu"];
$net_price = $invoices_json[0]["products"][0]["net_price"];
$quantity = $invoices_json[0]["products"][0]["quantity"];
$unit = $invoices_json[0]["products"][0]["unit"];
$net_value = $invoices_json[0]["products"][0]["net_value"];
$vat_percent = $invoices_json[0]["products"][0]["vat_percent"];
$vat_value = $invoices_json[0]["products"][0]["vat_value"];
$gross_value = $invoices_json[0]["products"][0]["gross_value"];
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
        <td width="20" align="center">$lp</td>
        <td width="200" align="center">$name</td>
        <td width="40" align="center">$pkwiu</td>
        <td width="70" align="center">$net_price</td>
        <td width="30" align="center">$quantity</td>
        <td width="30" align="center">$unit</td>
        <td width="70" align="center">$net_value</td>
        <td width="30" align="center">$vat_percent</td>
        <td width="60" align="center">$vat_value</td>
        <td width="70" align="center">$gross_value</td>
    </tr>
    <tr align="left">
        <td width="390" align="right">Razem</td>
        <td width="70" align="center">$net_value</td>
        <td width="30" align="center">$vat_percent</td>
        <td width="60" align="center">$vat_value</td>
        <td width="70" align="center">$gross_value</td>
    </tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$to_pay = $invoices_json[0]["to_pay"];
$to_pay_words = $invoices_json[0]["to_pay_words"];
$payment_method = $invoices_json[0]["payment_method"];
$account_nr = $invoices_json[0]["account_number"];
$pay_date = new DateTime($todays_date);
$pay_date->modify("+14 day");
$pay_date_str = $pay_date->format("Y-m-d");
$tbl = <<<EOD
<br />
<br />
<table cellspacing="10" cellpadding="1" border="0">
    <tr style="background-color:#FFFF00;color:#0000FF;">
        <td style="background-color:#FFFFFF;color:#FFFFFF;"></td>
        <td align="left"><b>Do zaplaty</b></td>
        <td align="right">$to_pay</td>
    </tr>
    <tr>
        <td style="background-color:#FFFFFF;color:#FFFFFF;"></td>
        <td align="left">Slownie:<br />Sposob zaplaty:<br />Termin:<br />Rachunek:</td>
        <td align="right">$to_pay_words<br />$payment_method<br />$pay_date_str<br />$account_nr</td>
    </tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $invoices_json[0]["identifier"] . '.pdf';
$base64_pdf = $pdf->Output($pdf_path, 'F');
send_invoice_email($pdf_path, $invoices_json[0]["email_text"], $invoices_json[0]["email"]);
