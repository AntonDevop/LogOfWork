<?php

include 'dbconnection.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

if(isset($_POST["customExport"])){
    /** Create a new Spreadsheet Object **/
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    //checking is written/verbal checked: variables value true/false
    $written = isset($_POST["writtenType"]);
    $verbal = isset($_POST["verbalType"]);
    
/* 1.0 Parameters section processing ↓ */
    /* 1.1 Dates selection ↓ */
    if(isset($_POST["allDates"])){
        $startDate = "2018-03-01";
        $startDate = date('Y-m-d',strtotime($startDate));
        $finishDate = date('Y-m-d');
    } else {
        $startDate = date('Y-m-d',strtotime($_POST["dateStarted"]));
        $finishDate = date('Y-m-d',strtotime($_POST["dateFinished"]));
    }
    /* 1.1 Dates selection ↑ */
    
    /* 1.2 Departments selection ↓ */
    if(isset($_POST["allDepartments"])){
        $departmentsArray = "all";        
    } else {
        $departmentsArray = [];
        $numberOfDepartments = $_POST["numberofDepartments"];
        $i = 0;
        $number = 1;
        while ($i < $numberOfDepartments){
            if(isset($_POST["numberofDepartments".$number.""])){
                $departmentsArray[] = $_POST["numberofDepartments".$number.""];                
            }
        }
    }
    /* 1.2 Departments selection ↑ */
    
    /* 1.3 Translators selection ↓ */
    if(isset($_POST["allTranslators"])){
        $translatorsArray = "all";        
    } else {
        $translatorsArray = [];
        $numberOfTranslators = $_POST["numberOfTranslators"];
        $i = 0;
        while ($i < $numberOfTranslators){
            if(isset($_POST["translatorCheck".$i.""])){
                $translatorsArray[] = $_POST["translatorCheck".$i.""];                
            }
        }
    }
    /* 1.3 Translators selection ↑ */
    
    /* 1.4 Symbols selection ↓ */
    if($written){
        if(isset($_POST["symbolsCheckbox"])){
            $symbolsFrom = 0;
            $symbolsTo = 50000;    
        } else {
            $symbolsFrom = $_POST["symbolsFrom"];
            $symbolsTo = $_POST["symbolsTo"];
        }
    }
    /* 1.4 Symbols selection ↑ */
    
    /* 1.5 Duration selection ↓ */
    if($verbal){
        if(isset($_POST["durationCheckbox"])){
            $symbolsFrom = 0;
            $symbolsTo = 720;    
        } else {
            $durationFrom = $_POST["durationFrom"];
            $durationTo = $_POST["durationTo"];
        }
    }
    /* 1.5 Duration selection ↑ */
            
/* 1.0 Parameters section processing ↑ */   
    
    
    
/* 2.0 Written section processing ↓ */
    if($written){
    /* 2.1 Written by department ↓ */
        /* 2.1.1 Fetching writtenDB to array  ↓ */
        if($departmentsArray == "all"){
            $sqlDept = "SELECT SUM(symbols), requesterDepartment 
                        FROM `writtenDB` 
                        WHERE `dateFinished` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `symbols` BETWEEN '".$symbolsFrom."' AND '".$symbolsTo."'
                        GROUP BY requesterDepartment";
        } else {
            //WHERE condition from array for departments 
            $sqlDept = "SELECT SUM(symbols), requesterDepartment 
            FROM `writtenDB` 
            WHERE `dateFinished` BETWEEN '".$startDate."' AND '".$finishDate."'
                AND `symbols` BETWEEN '".$symbolsFrom."' AND '".$symbolsTo."'
                AND `requesterDepartment` IN ('".$$departmentsArray."') 
            GROUP BY requesterDepartment";
        }
        
        $resultsqlDept = mysqli_query($database, $sqlDept);
        
        if($resultsqlDept->num_rows > 0) {
            $writtenDeptArray = [];
            while($row = $resultsqlDept->fetch_assoc()) {
                $writtenDeptArray[] = array("requesterDepartment"=>$row["requesterDepartment"],"symbols"=>$row["SUM(symbols)"],
                                           );
            }
        }

        /* 2.1.1 Fetching writtenDB to array  ↑ */
        
        /* 2.1.2 Enter data from array to spreadsheet  ↓ */
        
        
        /* 2.1.2 Enter data from array to spreadsheet  ↑ */                
    /* 2.1 Written by department ↑ */

    /* 2.2 Written by name ↓ */
        /* 2.1.1 Fetching writtenDB to  ↓ */
        $sqlDept = "SELECT SUM(symbols), requesterDepartment 
                    FROM `writtenDB` 
                    WHERE dateFinished BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY requesterDepartment";
        /* 2.1.1 Fetching writtenDB to  ↑ */
        
        /* 2.1.2 Enter data from array to spreadsheet  ↓ */
        // Create a new worksheet called "Written by departments"
        $writtenDept = new \PhpOffice\PhpSpreadsheet\Worksheet($spreadsheet, 'Written by departments');
        
        // Attach the "My Data" worksheet as the first worksheet in the Spreadsheet object
        $spreadsheet->addSheet($writtenDept, 0);
        
        //removing temporary created worksheet
        $spreadsheet->removeSheetByIndex(1);
        
        $spreadsheet->getSheet(0);
        
        $columnA = "A";
        $columnB = "B";
        $columnC = "C";
        $columnD = "D";
        
        //naming Headers for spreadsheet
        $sheet->setCellValue('A1', 'Selector');
        $sheet->setCellValue('B1', 'Symbols or minutes');
        $sheet->setCellValue('C1', 'Pages or hours');
        $sheet->setCellValue('D1', 'Percent');        
        
        
        
        /* 2.1.2 Enter data from array to spreadsheet  ↑ */  
    /* 2.2 Written by name ↑ */
    }
/* 2.0 Written section processing ↑ */



/* 3.0 Verbal section processing ↓ */
    if($verbal){
    /* 3.1 Verbal by department ↓ */
    
    /* 3.1 Verbal by department ↑ */

    /* 3.2 Verbal by name ↓ */

    /* 3.2 Verbal by name ↑ */

    }
/* 3.0 Verbal section processing ↑ */



/* 4.0 Translators section processing ↓ */

    /* 4.1 Written by translators ↓ */
    
    /* 4.1 Written by translators ↑ */

    /* 4.2 Verbal by translators ↓ */

    /* 4.2 Verbal by translators ↑ */

/* 4.0 Translators section processing ↑ */

}

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