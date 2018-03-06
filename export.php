<?php
include 'dbconnection.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$columnA = "A";
$columnB = "B";
$columnC = "C";
$columnD = "D";
$columnE = "E";
$rowNumber = 2;

$output = '';

if(isset($_POST["export"])) {
    $sql = "SELECT * FROM report";
    $result = mysqli_query($database, $sql);
    if(mysqli_num_rows($result) > 0) {
        $sheet->setCellValue('A1', 'Selector');
        $sheet->setCellValue('B1', 'Symbols or minutes');
        $sheet->setCellValue('C1', 'Pages or hours');
        $sheet->setCellValue('D1', 'Percent');
        $sheet->setCellValue('E1', 'Department');
        
        while($row = mysqli_fetch_array($result)) {
            if($row["selector"] == "") {
                $sheet->setCellValue(''.$columnA.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnE.''.$rowNumber.'', '');
                $rowNumber += 1;
                continue;
            }
            
            if($row["selector"] == "Written translations by Translators") {
                $sheet->setCellValue(''.$columnA.''.$rowNumber.'', 'Statistics by Translators');
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnE.''.$rowNumber.'', '');
                $styleByTranslators = array(
                    'font' => array(
                        'bold' => true,
                    ),
                    'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                );
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowNumber.':D'.$rowNumber.'')->applyFromArray($styleByTranslators);
                $rowNumber += 1;
                continue;
            }
            
            //A2... Selector name
            $sheet->setCellValue(''.$columnA.''.$rowNumber.'', ''.$row["selector"].'');
            
            //B2... Total number
            $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$row["total"].'');
            
            //C2... Page or hours number
            $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$row["pageshours"].'');
            
            //Formatting row as number
            $spreadsheet->getActiveSheet()->getStyle(''.$columnC.''.$rowNumber.'')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            
            //D2... Percent
            $sheet->setCellValue(''.$columnD.''.$rowNumber.'', ''.number_format($row["percent"], 0, ",", "").'');
            
            //E2... Department
            $sheet->setCellValue(''.$columnE.''.$rowNumber.'', ''.$row["department"].'');
            
            //Highlighting Written and Verbal section
            if($row["selector"] == "Written translations by Departments" || $row["selector"] == "Verbal translations by Departments") {
            
                $styleSection = array(
                    'font' => array(
                        'bold' => true,
                    ),
                    'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                );
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowNumber.':D'.$rowNumber.'')->applyFromArray($styleSection);
            }
            
            //Highlighting Written and Verbal section by name in italic
            if($row["selector"] == "Written translations by Names" || $row["selector"] == "Verbal translations by Names" || $row["selector"] == "Written translations by Translators" || $row["selector"] == "Verbal translations by Translators") {
            
                $styleSubSection = array(
                    'font' => array(
                        'italic' => true,
                    ),
                    'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                );
                $spreadsheet->getActiveSheet()->getStyle('A'.$rowNumber.':D'.$rowNumber.'')->applyFromArray($styleSubSection);
                
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', '');
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '');
            }   
            
            $rowNumber += 1;

        }
        //Setting auto width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        
        //Setting column alignment to center
        $spreadsheet->getActiveSheet()->getStyle('B1:D10000')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('B1:D10000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        /*
        $spreadsheet->getActiveSheet()->getStyle('C')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('C')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        */
    }

$weekNumber = gmdate('W');
$yearNumber = gmdate('Y');
// Redirect output to a client’s web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Report_weekNo_'.$weekNumber.'_year_'.$yearNumber.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$writer = IOFactory::createWriter($spreadsheet, 'Xls');
$writer->save('php://output');
exit;
    
}
?>