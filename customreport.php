<?php

include 'dbconnection.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use phpoffice\PhpSpreadsheet\Worksheet\Worksheet;
use phpoffice\PhpSpreadsheet\Style\Alignment;


if(isset($_POST["customExportButton"])){
    /** Create a new Spreadsheet Object **/
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    //checking is written/verbal checked: variables value true/false
    $written = isset($_POST["writtenType"]);
    $verbal = isset($_POST["verbalType"]);
    
/* 1.0 Parameters section processing ↓ */
    /* 1.1 Dates selection ↓ */
    if(isset($_POST["allDates"])){
        $startDate = "2018-01-01";
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
            if(isset($_POST["departmentCheck".$number.""])){
                $departmentsArray[] = $_POST["departmentCheck".$number.""];
            }
            $i++;
            $number++;
        }
    }
    /* 1.2 Departments selection ↑ */
    
    /* 1.3 Translators selection ↓ */
    if(isset($_POST["allTranslators"])){
        //inserting all names from users table
        //$translatorsArray = [];
        $sqlAllTranslators = "SELECT `name` FROM `usersTable`";
        $resultAllTranslators = mysqli_query($database, $sqlAllTranslators);
        
        while ($row = $resultAllTranslators->fetch_assoc()) {
            foreach($row as $key => $value) {
                $translatorsArray[]= $value;                
            }
        }
        
        $translatorsArray = implode("','", $translatorsArray);
        
    } else {
        $translatorsArray = [];
        $numberOfTranslators = $_POST["numberOfTranslators"];
        $i = 0;
        while ($i < $numberOfTranslators){
            if(isset($_POST["translatorCheck".$i.""])){
                $translatorsArray[] = $_POST["translatorCheck".$i.""];
            }
            $i++;
        }
        
        $translatorsArray = implode("','",$translatorsArray);
        
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
            $durationFrom = 0;
            $durationTo = 720;    
        } else {
            $durationFrom = $_POST["durationFrom"];
            $durationTo = $_POST["durationTo"];
        }
    }
    /* 1.5 Duration selection ↑ */
            
/* 1.0 Parameters section processing ↑ */   
        function AutoSize($x){
            //Setting auto width
            $x->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $x->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $x->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $x->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        }
        
        function BoldCentering($x){
            //applying bold style to headers
                $styleByTranslators = array(
                    'font' => array(
                        'bold' => true,
                    ),
                    'alignment' => array(
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ),
                );
                $x->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleByTranslators);
        }
        
/* 2.0 Written section processing ↓ */
    if($written){
    /* 2.1 Written by department ↓ */
        /* 2.1.1 Fetching writtenDB to array  ↓ */
        if($departmentsArray == "all"){
            $sqlDept = "SELECT SUM(symbols), requesterDepartment 
                        FROM `writtenDB` 
                        WHERE `dateFinished` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `symbols` BETWEEN '".$symbolsFrom."' AND '".$symbolsTo."'
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY requesterDepartment";
        } else {
            //transforming $departmentsArray into MySQL suitable string for WHERE `xxx` IN operator
            $departmentsArray = implode("','",$departmentsArray);
    
            $sqlDept = "SELECT SUM(symbols), requesterDepartment 
                        FROM `writtenDB` 
                        WHERE `dateFinished` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `symbols` BETWEEN '".$symbolsFrom."' AND '".$symbolsTo."'
                            AND `requesterDepartment` IN ('".$departmentsArray."')
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY requesterDepartment";
    
        }
        
        $resultsqlDept = mysqli_query($database, $sqlDept);
        
        //fetching data from DB into array
        if($resultsqlDept->num_rows > 0) {
            while($row = $resultsqlDept->fetch_assoc()) {
                $writtenArrayFilteredByDepartment[] = array("requesterDepartment"=>$row["requesterDepartment"],"symbols"=>$row["SUM(symbols)"]);
            }
        }

        //sorting 2D array by "symbols" column
        array_multisort(array_column($writtenArrayFilteredByDepartment, 'symbols'), SORT_DESC, $writtenArrayFilteredByDepartment);

        /* 2.1.1 Fetching writtenDB to array  ↑ */
        
        /* 2.1.2 Enter data from array to spreadsheet  ↓ */
        //$spreadsheet = new Spreadsheet();
        // Create a new worksheet called "Written by departments"
        $writtenDept = new Worksheet($spreadsheet, 'Written by departments');
        
        // Attach the "Written by departments" worksheet as the first worksheet in the Spreadsheet object
        $spreadsheet->addSheet($writtenDept, 0);
        
        //removing temporary created worksheet
        $spreadsheet->removeSheetByIndex(1);
        
        $sheet = $spreadsheet->getSheet(0);
        
        $columnA = "A";
        $columnB = "B";
        $columnC = "C";
        $columnD = "D";
        $rowNumber = 2;
        
        //naming Headers for spreadsheet
        $sheet->setCellValue('A1', 'Departments');
        $sheet->setCellValue('B1', 'Symbols');
        $sheet->setCellValue('C1', 'Pages');
        $sheet->setCellValue('D1', 'Percent');
    
        $lengthOfWrittenArray = count($writtenArrayFilteredByDepartment);
        $i = 0;
        //finding total number of symbols
        $totalSymbols = 0;
        while($i < $lengthOfWrittenArray){
            $totalSymbols += $writtenArrayFilteredByDepartment[$i]["symbols"];
            $i++;
        }
        $i = 0;

        while($i < $lengthOfWrittenArray){
            $pages = round($writtenArrayFilteredByDepartment[$i]["symbols"] / 1800, 2);
            
            //A2... Department name
            $sheet->setCellValue(''.$columnA.''.$rowNumber.'', ''.$writtenArrayFilteredByDepartment[$i]["requesterDepartment"].'');
            
            //B2... Symbols
            $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$writtenArrayFilteredByDepartment[$i]["symbols"].'');
            
            //C2... Pages
            $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$pages.'');
            
            //D2... Percent
            $percent = $writtenArrayFilteredByDepartment[$i]["symbols"] * 100 / $totalSymbols;            
            $sheet->setCellValue(''.$columnD.''.$rowNumber.'', ''.$percent.'');
            //formatting cell to hide all decimals
            $sheet->getStyle(''.$columnD.''.$rowNumber.'')->getNumberFormat()->setFormatCode('#,##0');
            
            $i++;
            $rowNumber++;
            
            //last row with summary
            if($i == $lengthOfWrittenArray){
                $rowNumber++;
                $totalPages = round($totalSymbols / 1800, 2);
                
                //A99... Total
                $sheet->setCellValue(''.$columnA.''.$rowNumber.'', 'Total');
                
                //B99... Total Symbols
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$totalSymbols.'');
                
                //C99... Pages
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$totalPages.'');
                
                //D99... Percent
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '100');
            }
            
        }
        

        AutoSize($spreadsheet);
        BoldCentering($spreadsheet);

        /* 2.1.2 Enter data from array to spreadsheet  ↑ */                
    /* 2.1 Written by department ↑ */

    /* 2.2 Written by name ↓ */
        /* 2.2.1 Fetching writtenDB to array  ↓ */
        if($departmentsArray == "all"){
            $sqlName = "SELECT SUM(symbols), requesterName 
                        FROM `writtenDB` 
                        WHERE `dateFinished` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `symbols` BETWEEN '".$symbolsFrom."' AND '".$symbolsTo."'
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY requesterName";
        } else {            
    
            $sqlName = "SELECT SUM(symbols), requesterName 
                        FROM `writtenDB` 
                        WHERE `dateFinished` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `symbols` BETWEEN '".$symbolsFrom."' AND '".$symbolsTo."'
                            AND `requesterDepartment` IN ('".$departmentsArray."')
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY requesterName";
        }
        
        $resultsqlName = mysqli_query($database, $sqlName);
        
        //fetching data from DB into array
        if($resultsqlName->num_rows > 0) {
            while($row = $resultsqlName->fetch_assoc()) {
                $writtenArrayFilteredByName[] = array("requesterName"=>$row["requesterName"],"symbols"=>$row["SUM(symbols)"]);
            }
        }
        
        //sorting 2D array by "symbols" column
        array_multisort (array_column($writtenArrayFilteredByName, 'symbols'), SORT_DESC, $writtenArrayFilteredByName);
        
        /* 2.2.1 Fetching writtenDB to array ↑ */
        
        /* 2.2.2 Enter data from array to spreadsheet  ↓ */
        
        // Create a new worksheet called "Written by names"
        $writtenName = new Worksheet($spreadsheet, 'Written by names');
        
        // Attach the "Written by names" worksheet as the second worksheet in the Spreadsheet object
        $spreadsheet->addSheet($writtenName, 1);
        
        $sheet = $spreadsheet->getSheet(1);
        
        $columnA = "A";
        $columnB = "B";
        $columnC = "C";
        $columnD = "D";
        $rowNumber = 2;
        
        //naming Headers for spreadsheet
        $sheet->setCellValue('A1', 'Names');
        $sheet->setCellValue('B1', 'Symbols');
        $sheet->setCellValue('C1', 'Pages');
        $sheet->setCellValue('D1', 'Percent');

        
        $lengthOfWrittenArray = count($writtenArrayFilteredByName);
        
        $i = 0;

        while($i < $lengthOfWrittenArray){
            $pages = round($writtenArrayFilteredByName[$i]["symbols"] / 1800, 2);
            
            //A2... Requester name
            $sheet->setCellValue(''.$columnA.''.$rowNumber.'', ''.$writtenArrayFilteredByName[$i]["requesterName"].'');
            
            //B2... Symbols
            $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$writtenArrayFilteredByName[$i]["symbols"].'');
            
            //C2... Pages
            $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$pages.'');
            
            //D2... Percent
            $percent = $writtenArrayFilteredByName[$i]["symbols"] * 100 / $totalSymbols;            
            $sheet->setCellValue(''.$columnD.''.$rowNumber.'', ''.$percent.'');
            $sheet->getStyle(''.$columnD.''.$rowNumber.'')->getNumberFormat()->setFormatCode('#,##0');
            
                        
            $i++;
            $rowNumber++;
            
            //last row with summary
            if($i == $lengthOfWrittenArray){
                $rowNumber++;
                $totalPages = round($totalSymbols / 1800, 2);
                
                //A99... Total
                $sheet->setCellValue(''.$columnA.''.$rowNumber.'', 'Total');
                
                //B99... Total Symbols
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$totalSymbols.'');
                
                //C99... Pages
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$totalPages.'');
                
                //D99... Percent
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '100');
            }            
            
        }
        
        AutoSize($spreadsheet);
        BoldCentering($spreadsheet);
            
        /* 2.2.2 Enter data from array to spreadsheet  ↑ */
    /* 2.2 Written by name ↑ */
    }  // if($written){
/* 2.0 Written section processing ↑ */



/* 3.0 Verbal section processing ↓ */
    if($verbal){
    /* 3.1 Verbal by department ↓ */
    
    /* 3.1 Verbal by department ↑ */
        /* 3.1.1 Fetching verbalDB to array  ↓ */
        if($departmentsArray == "all"){
            $sqlDept = "SELECT SUM(duration), requesterDepartment 
                        FROM `verbalDB` 
                        WHERE `date` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `duration` BETWEEN '".$durationFrom."' AND '".$durationTo."'
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY requesterDepartment";
        } else {
            if(!$written){
                //transforming $departmentsArray into MySQL suitable string for WHERE `xxx` IN operator
                $departmentsArray = implode("','",$departmentsArray);
            }

            $sqlDept = "SELECT SUM(duration), requesterDepartment 
                        FROM `verbalDB` 
                        WHERE `date` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `duration` BETWEEN '".$durationFrom."' AND '".$durationTo."'
                            AND `requesterDepartment` IN ('".$departmentsArray."')
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY requesterDepartment";
    
        }
        
        $resultsqlDept = mysqli_query($database, $sqlDept);
        
        //fetching data from DB into array
        if($resultsqlDept->num_rows > 0) {
            while($row = $resultsqlDept->fetch_assoc()) {
                $verbalArrayFilteredByDepartment[] = array("requesterDepartment"=>$row["requesterDepartment"],"duration"=>$row["SUM(duration)"]);
            }
        }

        //sorting 2D array by "duration" column
        array_multisort(array_column($verbalArrayFilteredByDepartment, 'duration'), SORT_DESC, $verbalArrayFilteredByDepartment);
        
        /* 3.1.1 Fetching verbalDB to array  ↑ */        
        

        /* 3.1.2 Enter data from array to spreadsheet  ↓ */
        //$spreadsheet = new Spreadsheet();
        // Create a new worksheet called "Verbal by departments"
        $verbalDept = new Worksheet($spreadsheet, 'Verbal by departments');
        
        if(!$written){
            
            // Attach the "Verbal by departments" worksheet as the first worksheet in the Spreadsheet object
            $spreadsheet->addSheet($verbalDept, 0);

            //removing temporary created worksheet
            $spreadsheet->removeSheetByIndex(1);            
            
            $sheet = $spreadsheet->getSheet(0);
            
        } else {
         
            // Attach the "Verbal by departments" worksheet as the third worksheet in the Spreadsheet object (after written)
            $spreadsheet->addSheet($verbalDept, 2);
            
            $sheet = $spreadsheet->getSheet(2);
            
        }
        
        
        $columnA = "A";
        $columnB = "B";
        $columnC = "C";
        $columnD = "D";
        $rowNumber = 2;
        
        //naming Headers for spreadsheet
        $sheet->setCellValue('A1', 'Departments');
        $sheet->setCellValue('B1', 'Duration');
        $sheet->setCellValue('C1', 'Hours');
        $sheet->setCellValue('D1', 'Percent');
    
        $lengthOfVerbalArray = count($verbalArrayFilteredByDepartment);
        $i = 0;
        //finding total number of duration
        $totalDuration = 0;
        while($i < $lengthOfVerbalArray){
            $totalDuration += $verbalArrayFilteredByDepartment[$i]["duration"];
            $i++;
        }
        
        $i = 0;
        while($i < $lengthOfVerbalArray){
            $hours = round($verbalArrayFilteredByDepartment[$i]["duration"] / 60, 2);
            
            //A2... Department name
            $sheet->setCellValue(''.$columnA.''.$rowNumber.'', ''.$verbalArrayFilteredByDepartment[$i]["requesterDepartment"].'');
            
            //B2... Duration
            $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$verbalArrayFilteredByDepartment[$i]["duration"].'');
            
            //C2... Pages
            $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$hours.'');
            
            //D2... Percent
            $percent = $verbalArrayFilteredByDepartment[$i]["duration"] * 100 / $totalDuration;            
            $sheet->setCellValue(''.$columnD.''.$rowNumber.'', ''.$percent.'');
            //formatting cell to hide all decimals
            $sheet->getStyle(''.$columnD.''.$rowNumber.'')->getNumberFormat()->setFormatCode('#,##0');
            
            $i++;
            $rowNumber++;
            
            //last row with summary
            if($i == $lengthOfVerbalArray){
                $rowNumber++;
                $totalHours = round($totalDuration / 60, 2);
                
                //A99... Total
                $sheet->setCellValue(''.$columnA.''.$rowNumber.'', 'Total');
                
                //B99... Total Duration
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$totalDuration.'');
                
                //C99... Hours
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$totalHours.'');
                
                //D99... Percent
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '100');
            }            
        }
        
        AutoSize($spreadsheet);
        BoldCentering($spreadsheet);

        /* 3.1.2 Enter data from array to spreadsheet  ↑ */                        
    /* 3.2 Verbal by name ↓ */
        /* 3.2.1 Fetching verbalDB to  ↓ */
        if($departmentsArray == "all"){
            $sqlName = "SELECT SUM(duration), requesterName 
                        FROM `verbalDB` 
                        WHERE `date` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `duration` BETWEEN '".$durationFrom."' AND '".$durationTo."'
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY requesterName";
        } else {            
    
            $sqlName = "SELECT SUM(duration), requesterName 
                        FROM `verbalDB` 
                        WHERE `date` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `duration` BETWEEN '".$durationFrom."' AND '".$durationTo."'
                            AND `requesterDepartment` IN ('".$departmentsArray."')
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY requesterName";
        }
        
        $resultsqlName = mysqli_query($database, $sqlName);
        
        //fetching data from DB into array
        if($resultsqlName->num_rows > 0) {
            while($row = $resultsqlName->fetch_assoc()) {
                $verbalArrayFilteredByName[] = array("requesterName"=>$row["requesterName"],"duration"=>$row["SUM(duration)"]);
            }
        }
        
        //sorting 2D array by "duration" column
        array_multisort (array_column($verbalArrayFilteredByName, 'duration'), SORT_DESC, $verbalArrayFilteredByName);
        
        /* 3.2.1 Fetching verbalDB to  ↑ */
        
    
        /* 3.2.2 Enter data from array to spreadsheet  ↓ */
        // Create a new worksheet called "Verbal by names"
        $verbalName = new Worksheet($spreadsheet, 'Verbal by names');
        
        if(!$written){
            // Attach the "Verbal by names" worksheet as the second worksheet in the Spreadsheet object
            $spreadsheet->addSheet($verbalName, 1);

            $sheet = $spreadsheet->getSheet(1);            
        } else {
            // Attach the "Verbal by names" worksheet as the fourth worksheet in the Spreadsheet object
            $spreadsheet->addSheet($verbalName, 3);

            $sheet = $spreadsheet->getSheet(3);
        }
        
        $columnA = "A";
        $columnB = "B";
        $columnC = "C";
        $columnD = "D";
        $rowNumber = 2;
        
        //naming Headers for spreadsheet
        $sheet->setCellValue('A1', 'Names');
        $sheet->setCellValue('B1', 'Duration');
        $sheet->setCellValue('C1', 'Hours');
        $sheet->setCellValue('D1', 'Percent');
        
        $lengthOfVerbalArray = count($verbalArrayFilteredByName);
        
        $i = 0;

        while($i < $lengthOfVerbalArray){
            $hours = round($verbalArrayFilteredByName[$i]["duration"] / 60, 2);
            
            //A2... Requester name
            $sheet->setCellValue(''.$columnA.''.$rowNumber.'', ''.$verbalArrayFilteredByName[$i]["requesterName"].'');
            
            //B2... Duration
            $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$verbalArrayFilteredByName[$i]["duration"].'');
            
            //C2... Hours
            $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$hours.'');
            
            //D2... Percent
            $percent = $verbalArrayFilteredByName[$i]["duration"] * 100 / $totalDuration;            
            $sheet->setCellValue(''.$columnD.''.$rowNumber.'', ''.$percent.'');
            $sheet->getStyle(''.$columnD.''.$rowNumber.'')->getNumberFormat()->setFormatCode('#,##0');
            
            $i++;
            $rowNumber++;
            
            //last row with summary
            if($i == $lengthOfVerbalArray){
                $rowNumber++;
                $totalHours = round($totalDuration / 60, 2);
                
                //A99... Total
                $sheet->setCellValue(''.$columnA.''.$rowNumber.'', 'Total');
                
                //B99... Total Duration
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$totalDuration.'');
                
                //C99... Hours
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$totalHours.'');
                
                //D99... Percent
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '100');
            }                
        }
        
        AutoSize($spreadsheet);
        BoldCentering($spreadsheet);
        /* 3.2.2 Enter data from array to spreadsheet  ↑ */        
    }
    /* 3.2 Verbal by name ↑ */
/* 3.0 Verbal section processing ↑ */



/* 4.0 Translators section processing ↓ */

    /* 4.1 Written by translators ↓ */
        /* 4.1.1 Fetching writtenDB to array ↓ */
    if($written){
        if($departmentsArray == "all"){            
            $sqlDoneBy = "SELECT SUM(symbols), doneBy 
                        FROM `writtenDB` 
                        WHERE `dateFinished` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `symbols` BETWEEN '".$symbolsFrom."' AND '".$symbolsTo."'
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY doneBy";
        } else {
            $sqlDoneBy = "SELECT SUM(symbols), doneBy 
                        FROM `writtenDB` 
                        WHERE `dateFinished` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `symbols` BETWEEN '".$symbolsFrom."' AND '".$symbolsTo."'
                            AND `requesterDepartment` IN ('".$departmentsArray."')
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY doneBy";            
        }
        
        $resultsqlDoneBy = mysqli_query($database, $sqlDoneBy);
        
        //fetching data from DB into array
        if($resultsqlDoneBy->num_rows > 0) {
            while($row = $resultsqlDoneBy->fetch_assoc()) {
                $writtenArrayDoneBy[] = array("doneBy"=>$row["doneBy"],"symbols"=>$row["SUM(symbols)"]);
            }
        }
        
        //sorting 2D array by "symbols" column
        array_multisort (array_column($writtenArrayDoneBy, 'symbols'), SORT_DESC, $writtenArrayDoneBy);
        
        /* 4.1.1 Fetching writtenDB to array ↑ */
        
        /* 4.1.2 Enter data from array to spreadsheet  ↓ */
        
        // Create a new worksheet called "Written by names"
        $writtenTranslators = new Worksheet($spreadsheet, 'Written by translators');
        
        if($verbal){
            // Attach the "Written by Translators" worksheet as the fifth worksheet in the Spreadsheet object
            $spreadsheet->addSheet($writtenTranslators, 4);
            $sheet = $spreadsheet->getSheet(4);
        } else {
            // Attach the "Written by Translators" worksheet as the fifth worksheet in the Spreadsheet object
            $spreadsheet->addSheet($writtenTranslators, 2);
            $sheet = $spreadsheet->getSheet(2);
        }
        
        $columnA = "A";
        $columnB = "B";
        $columnC = "C";
        $columnD = "D";
        $rowNumber = 2;
        
        //naming Headers for spreadsheet
        $sheet->setCellValue('A1', 'Translators');
        $sheet->setCellValue('B1', 'Symbols');
        $sheet->setCellValue('C1', 'Pages');
        $sheet->setCellValue('D1', 'Percent');        
        

        $lengthOfWrittenArray = count($writtenArrayDoneBy);
        
        $i = 0;

        while($i < $lengthOfWrittenArray){
            $pages = round($writtenArrayDoneBy[$i]["symbols"] / 1800, 2);
            
            //A2... Requester name
            $sheet->setCellValue(''.$columnA.''.$rowNumber.'', ''.$writtenArrayDoneBy[$i]["doneBy"].'');
            
            //B2... Symbols
            $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$writtenArrayDoneBy[$i]["symbols"].'');
            
            //C2... Pages
            $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$pages.'');
            
            //D2... Percent
            $percent = $writtenArrayDoneBy[$i]["symbols"] * 100 / $totalSymbols;            
            $sheet->setCellValue(''.$columnD.''.$rowNumber.'', ''.$percent.'');
            $sheet->getStyle(''.$columnD.''.$rowNumber.'')->getNumberFormat()->setFormatCode('#,##0');
            
                        
            $i++;
            $rowNumber++;
            
            //last row with summary
            if($i == $lengthOfWrittenArray){
                $rowNumber++;
                $totalPages = round($totalSymbols / 1800, 2);
                
                //A99... Total
                $sheet->setCellValue(''.$columnA.''.$rowNumber.'', 'Total');
                
                //B99... Total Symbols
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$totalSymbols.'');
                
                //C99... Pages
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$totalPages.'');
                
                //D99... Percent
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '100');
            }            
            
        }
        
        AutoSize($spreadsheet);
        BoldCentering($spreadsheet);
        
        /* 4.1.2 Enter data from array to spreadsheet  ↑ */
        
    }
    /* 4.1 Written by translators ↑ */

    /* 4.2 Verbal by translators ↓ */
        /* 4.2.1 Fetching verbalDB to array ↓ */
    if($verbal){
        if($departmentsArray == "all"){            
            $sqlDoneBy = "SELECT SUM(duration), doneBy 
                        FROM `verbalDB` 
                        WHERE `date` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `duration` BETWEEN '".$durationFrom."' AND '".$durationTo."'
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY doneBy";
        } else {
            $sqlDoneBy = "SELECT SUM(duration), doneBy 
                        FROM `verbalDB` 
                        WHERE `date` BETWEEN '".$startDate."' AND '".$finishDate."'
                            AND `duration` BETWEEN '".$durationFrom."' AND '".$durationTo."'
                            AND `requesterDepartment` IN ('".$departmentsArray."')
                            AND `doneBy` IN ('".$translatorsArray."')
                        GROUP BY doneBy";            
        }
        
        $resultsqlDoneBy = mysqli_query($database, $sqlDoneBy);
        
        //fetching data from DB into array
        if($resultsqlDoneBy->num_rows > 0) {
            while($row = $resultsqlDoneBy->fetch_assoc()) {
                $verbalArrayDoneBy[] = array("doneBy"=>$row["doneBy"],"duration"=>$row["SUM(duration)"]);
            }
        }
        
        //sorting 2D array by "duration" column
        array_multisort (array_column($verbalArrayDoneBy, 'duration'), SORT_DESC, $verbalArrayDoneBy);
        
        /* 4.2.1 Fetching verbalDB to array ↑ */
        
        /* 4.2.2 Enter data from array to spreadsheet  ↓ */
        
        // Create a new worksheet called "Verbal by names"
        $verbalTranslators = new Worksheet($spreadsheet, 'Verbal by translators');
        
        if($written){
            // Attach the "Verbal by Translators" worksheet as the fifth worksheet in the Spreadsheet object
            $spreadsheet->addSheet($verbalTranslators, 4);
            $sheet = $spreadsheet->getSheet(4);
        } else {
            // Attach the "Verbal by Translators" worksheet as the fifth worksheet in the Spreadsheet object
            $spreadsheet->addSheet($verbalTranslators, 2);
            $sheet = $spreadsheet->getSheet(2);
        }
        
        $columnA = "A";
        $columnB = "B";
        $columnC = "C";
        $columnD = "D";
        $rowNumber = 2;
        
        //naming Headers for spreadsheet
        $sheet->setCellValue('A1', 'Translators');
        $sheet->setCellValue('B1', 'Duration');
        $sheet->setCellValue('C1', 'Hours');
        $sheet->setCellValue('D1', 'Percent');        
        

        $lengthOfVerbalArray = count($verbalArrayDoneBy);
        
        $i = 0;

        while($i < $lengthOfVerbalArray){
            $hours = round($verbalArrayDoneBy[$i]["duration"] / 60, 2);
            
            //A2... Requester name
            $sheet->setCellValue(''.$columnA.''.$rowNumber.'', ''.$verbalArrayDoneBy[$i]["doneBy"].'');
            
            //B2... Duration
            $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$verbalArrayDoneBy[$i]["duration"].'');
            
            //C2... Pages
            $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$hours.'');
            
            //D2... Percent
            $percent = $verbalArrayDoneBy[$i]["duration"] * 100 / $totalDuration;            
            $sheet->setCellValue(''.$columnD.''.$rowNumber.'', ''.$percent.'');
            $sheet->getStyle(''.$columnD.''.$rowNumber.'')->getNumberFormat()->setFormatCode('#,##0');
            
                        
            $i++;
            $rowNumber++;
            
            //last row with summary
            if($i == $lengthOfVerbalArray){
                $rowNumber++;
                $totalHours = round($totalDuration / 60, 2);
                
                //A99... Total
                $sheet->setCellValue(''.$columnA.''.$rowNumber.'', 'Total');
                
                //B99... Total duration
                $sheet->setCellValue(''.$columnB.''.$rowNumber.'', ''.$totalDuration.'');
                
                //C99... Hours
                $sheet->setCellValue(''.$columnC.''.$rowNumber.'', ''.$totalHours.'');
                
                //D99... Percent
                $sheet->setCellValue(''.$columnD.''.$rowNumber.'', '100');
            }            
            
        }
        
        AutoSize($spreadsheet);
        BoldCentering($spreadsheet);
        
        /* 4.2.2 Enter data from array to spreadsheet  ↑ */
        
    }
    $spreadsheet->setActiveSheetIndex(0);
    /* 4.2 Verbal by translators ↑ */
/* 4.0 Translators section processing ↑ */

    
// Redirect output to a client’s web browser (Xls)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Custom_Report.xls"');
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