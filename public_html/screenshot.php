<?php
/*ob_clean();
//$filteredData=substr($_POST['img_val'], strpos($_POST['img_val'], ",")+1);
//$unencodedData=base64_decode($filteredData);
//file_put_contents('img.png', $unencodedData);
$filename=date('Y-m-d')."report.png";
header("Content-type: image/png");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
readfile($_POST['img_val']);*/

include_once $_SERVER['DOCUMENT_ROOT']."/tcpdf/tcpdf.php";
// create new PDF document
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        //define ('K_PATH_IMAGES', '/img/');
	// Logo
        //$image_file = K_PATH_IMAGES.'reportbanner.png';
        $image_file = '/img/reportheader.png';
        $this->Image($image_file, 0, 5, 220, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

//$pdf->setPrintHeader(false);
$image = $_POST['img_val'];
$pdf->AddPage();
$html = '<div style="text-align:center;"><img src="'.$image.'" border="0" /></div>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reports.pdf', 'I');
?>
