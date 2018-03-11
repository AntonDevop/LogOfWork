<?php
session_start();


if (array_key_exists("name", $_COOKIE) AND array_key_exists("manager", $_COOKIE)) {
    
    $_SESSION['name'] = $_COOKIE['name'];
    $_SESSION['manager'] = $_COOKIE['manager'];

}

if (array_key_exists("name", $_SESSION) AND array_key_exists("manager", $_SESSION)) {
  
    $translatorName = $_SESSION["name"];
    $manager = $_SESSION["manager"];
    
} else {
    header("Location: index.php");    
}

include 'dbconnection.php';
?>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

    <title>Report page | LogOfWork</title>
    
    <link rel="stylesheet" href="css/main.css">
    
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Amaranth:700i" rel="stylesheet">
    
    <?php include 'metrics.php'; ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top mb-5 justify-content-between"  id="navbarId">
        <img id="logo" src="img/logoNav.png" alt="logo picture">
        <h2 class="yellowstrocked">Report page</h2>
        <div>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
               <?php 
                    if (array_key_exists("manager", $_SESSION)) {
                        echo '
                <a class="nav-item nav-link btn btn-warning mx-1 px-2" href="manager.php">Manager page <span class="sr-only">(manager)</span></a>
                <a class="nav-item nav-link btn btn-info mx-1 px-2" href="translators.php">Translators</a>
                        ';
                    }
                ?>
                
                <div class="dropdown">
                <a class="nav-item nav-link  dropdown-toggle btn btn-info mx-1 px-2" href="main.php" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Main page</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="main.php">Main</a>
                 <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="main.php#writtenTable">Written section</a>
                  <a class="dropdown-item" href="main.php#verbalTable">Verbal section</a>                 
                </div>   
                </div>
                
                <a class="nav-item nav-link btn btn-info mx-1 px-2" href="profile.php">My profile</a>    
                                
                <a class="nav-item nav-link btn btn-danger mx-1 px-2" href="logout.php">Log out</a>
            </div>
            </div>
        </div>
    </nav>
    
    
    <div class="container-fluid" id="afterNav">
       <div class="row justify-content-md-center">
            <table class="table text-center table-sm">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Your name</th>
                  <th scope="col">Week Start</th>
                  <th scope="col">Week End</th>                  
                </tr>
              </thead>
              <tbody class="border border-top-0 border-dark">
                <tr>
                  <th scope="row"><?php echo $translatorName; ?></th>
                  <td><?php echo date('F j, Y', strtotime($weekStart)); ?></td>
                  <td><?php echo date('F j, Y', strtotime($weekEnd)); ?></td>                  
                </tr>
              </tbody>
            </table>
       </div>
       <div class="row justify-content-center">
           <form action="export.php" method="post" name="export_excel">

                <div class="control-group">
                    <div class="controls">
                        <button type="submit" id="export" name="export" class="btn btn-warning my-3">Export to Excel File</button>
                    </div>
                </div>
            </form>
       </div>
       
       <!-- ******************************* -->
       <!-- Container for custom report ↓ -->	
        <div class="container">
            <div class="row justify-content-center">
                <a class="btn btn-info" data-toggle="collapse" href="#customReport" role="button" aria-expanded="false" aria-controls="editProfile" id="customReportButton">Custom report to Excel</a>
            </div>

            <!-- Collapsible area for a custom report option ↓ -->
            <div class="collapse row justify-content-center formbackground" id="customReport">

                <div class="row justify-content-center">
                    <h3>Please select what you want to include in a custom report</h3>
                </div>

                <form action="customreport.php" method="post" name="export_custom">

                    <!-- Checkboxes for types of translation ↓ -->
                    <div class="row justify-content-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="writtenType" id="inlineCheckbox1" value="written" checked data-toggle="collapse" data-target="#symbolsWrapper" aria-expanded="false" aria-controls="symbolsWrapper">
                            <label class="form-check-label typecheck" for="inlineCheckbox1">Written</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="verbalType" id="inlineCheckbox2" value="verbal" checked data-toggle="collapse" data-target="#durationWrapper" aria-expanded="false" aria-controls="durationWrapper">
                            <label class="form-check-label typecheck" for="inlineCheckbox2">Verbal</label>
                        </div>
                    </div>
                    <!-- Checkboxes for types of translation ↑ -->

                    <!-- Checkbox for dates ↓ -->
                    <div class="row justify-content-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allDates" id="datesCheckbox" value="datesAll" checked data-toggle="collapse" data-target="#collapsibleDates" aria-expanded="false" aria-controls="collapsibleDates" onclick="customDatesRequired();">
                            <label class="form-check-label typecheck" for="datesCheckbox">
                            All dates
                            <small class="text-secondary">(Uncheck to specify exact period)</small>
                            </label>
                        </div>
                    </div>
                    <!-- Checkbox for dates ↑ -->

                    <!-- Collapsible for date pickers ↓  -->
                    <div class="collapse" id="collapsibleDates">
                        <div class="row justify-content-center">

                            <div class="form-row">
                                <div class="col-sm">
                                    <label for="dateStarted">
                                    Date started
                                    </label>
                                    <input type="date" class="form-control" id="dateStarted" name="dateStarted" min="2018-01-01" max="2030-01-01" value="<?php echo date('d M Y'); ?>">
                                </div>
                                <div class="col-sm">
                                    <label for="dateFinished">
                                    Date finished
                                    </label>
                                    <input type="date" class="form-control" id="dateFinished" name="dateFinished" min="2018-01-01" max="2030-01-01" value="<?php echo date('d M Y'); ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Collapsible for date pickers ↑  -->


                    <!-- Checkbox for department ↓ -->
                    <div class="row justify-content-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allDepartments" id="departmentCheckbox" value="datesAll" checked data-toggle="collapse" data-target="#collapsibleDepartment" aria-expanded="false" aria-controls="collapsibleDepartment">
                            <label class="form-check-label typecheck" for="departmentCheckbox">
                            All departments 
                            <small class="text-secondary">(Uncheck to specify exact department(s))</small>
                            </label>
                        </div>
                    </div>
                    <!-- Checkbox for department ↑ -->

                    <!-- Collapsible for department ↓  -->
                    <div class="collapse" id="collapsibleDepartment">
                        <div class="checkboxesScrollable">
                        <?php 
                        $sql = "SELECT DISTINCT `Department` FROM `clients`";
                        $result = $database->query($sql);

                        //fetching all rows from clients table into associated array ↓
                        $departmentsArray = [];
                        while ($row = $result->fetch_assoc()) {
                            $departmentsArray[] = $row;
                        }
                        $numberofDepartments = count($departmentsArray);
                        //length of array from clients table

                        $listOfDepartments;
                        $counter = 0;
                        $number = 1;
                        while ($counter < $numberofDepartments){
                            $listOfDepartments += '
                                <!-- '.$number.'/'.$numberofDepartments.' row of departments ↓ -->
                                <div class="row justify-content-start">

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="'.$departmentsArray[$counter].'" name="departmentCheck'.$number.'"
                                        id="departmentCheck'.$number.'">
                                        <label class="form-check-label" for="departmentCheck'.$number.'">
                                             '.$departmentsArray[$counter].'
                                          </label>
                                    </div>

                                </div>
                                <!-- '.$number.'/'.$numberofDepartments.' row of departments ↑ -->                                                  
                            ';
                            $counter++;
                            $number++;
                        }

                        echo $listOfDepartments;

                        ?>                        
                        <input type="hidden" name="numberofDepartments" value="<?php echo $numberofDepartments; ?>">
                        </div>
                    </div>
                    <!-- Collapsible for department ↑  -->

                   
                    <!-- Checkbox for translators ↓ -->
                    <div class="row justify-content-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="allTranslators" id="translatorCheckbox" value="translatorsAll" checked data-toggle="collapse" data-target="#collapsibleTranslators" aria-expanded="false" aria-controls="collapsibleTranslators">
                            <label class="form-check-label typecheck" for="translatorCheckbox">
                            All translators 
                            <small class="text-secondary">(Uncheck to specify exact translator(s))</small>
                            </label>
                        </div>
                    </div>
                    <!-- Checkbox for translators ↑ -->
                    
                    <!-- Collapsible for translators ↓  -->
                    <div class="collapse" id="collapsibleTranslators">
                        <div class="row justify-content-center">
                            <div class="checkboxesScrollable">
                                
                                <?php 
                                $sql = "SELECT name FROM usersTable";
                                $result = $database->query($sql);

                                //fetching all rows from users table into associated array ↓
                                $translatorsArray = [];
                                while ($row = $result->fetch_assoc()) {
                                    $translatorsArray[] = $row;
                                }
                                $numberOfTranslators = count($translatorsArray);
                                //length of array from users table
                                
                                $listOfTranslators;
                                $counter = 0;
                                while ($counter < $numberOfTranslators){
                                    $listOfTranslators += '
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="'.$translatorsArray[$counter].'" name="translatorCheck'.$counter.'"
                                            id="translatorsCheck'.$counter.'">
                                            <label class="form-check-label" for="translatorsCheck'.$counter.'">
                                                 '.$translatorsArray[$counter].'
                                            </label>
                                        </div>                                    
                                    ';
                                    $counter++;
                                }
                                
                                echo $listOfTranslators;
                                
                                ?>
                                
                                <input type="hidden" name="numberOfTranslators" value="<?php echo $numberOfTranslators; ?>">                                                              
                                
                            </div>

                        </div>
                    </div>
                    <!-- Collapsible for translators ↑  -->                    
                    
                    
                    <!-- Wrapper for symbols appears after written is checked ↓ -->
                    <div class="collapse show" id="symbolsWrapper">
                    <!-- Checkbox for symbols ↓ -->
                    <div class="row justify-content-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="symbolsCheckbox" id="symbolsCheckbox" value="symbolsAll" checked data-toggle="collapse" data-target="#collapsibleSymbols" aria-expanded="false" aria-controls="collapsibleSymbols">
                            <label class="form-check-label typecheck" for="symbolsCheckbox">
                            All range of symbols 
                            <small class="text-secondary">(Uncheck to specify exact range of symbols2)</small>
                            </label>
                        </div>
                    </div>
                    <!-- Checkbox for symbols ↑ -->
                    </div>
                    <!-- Wrapper for symbols appears after written is checked ↑ -->

                    <!-- Collapsible for symbols picker ↓  -->
                    <div class="collapse" id="collapsibleSymbols">
                        <div class="row justify-content-center">

                            <div class="form-row">
                                <div class="col-sm">
                                    <label for="symbolsFrom">
                                    Symbols from
                                    </label>
                                    <input type="number" class="form-control" id="symbolsFrom" name="symbolsFrom" value="0">
                                </div>
                                <div class="col-sm">
                                    <label for="symbolsTo">
                                    Symbols to
                                    </label>
                                    <input type="number" class="form-control" id="symbolsTo" name="symbolsTo" value="50000">
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Collapsible for symbols picker ↑  -->
                                                         

                    <!-- Wrapper for duration appears after verbal is checked ↓ -->
                    <div class="collapse show" id="durationWrapper">
                    <!-- Checkbox for duration ↓ -->
                    <div class="row justify-content-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="durationCheckbox" id="durationCheckbox" value="durationAll" checked data-toggle="collapse" data-target="#collapsibleDuration" aria-expanded="false" aria-controls="collapsibleDuration">
                            <label class="form-check-label typecheck" for="durationCheckbox">
                            All range of duration for verbal translations
                            <small class="text-secondary">(Uncheck to specify exact range of duration)</small>
                            </label>
                        </div>
                    </div>
                    <!-- Checkbox for duration ↑ -->
                    </div>
                    <!-- Wrapper for duration appears after verbal is checked ↑ -->

                    <!-- Collapsible for duration picker ↓  -->
                    <div class="collapse" id="collapsibleDuration">
                        <div class="row justify-content-center">

                            <div class="form-row">
                                <div class="col-sm">
                                    <label for="durationFrom">
                                    Minutes from
                                    </label>
                                    <input type="number" class="form-control" id="durationFrom" name="durationFrom" value="0">
                                </div>
                                <div class="col-sm">
                                    <label for="durationTo">
                                    Minutes to
                                    </label>
                                    <input type="number" class="form-control" id="durationTo" name="durationTo" value="720">
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Collapsible for duration picker ↑  -->
                    
                    <div class="row justify-content-center">
                    <button type="submit" id="customExport" name="customExport" class="btn btn-success my-3">Save to Excel File</button>
                    </div>
                                                                                                                         
                </form>
            </div>
            </div>
            <!-- Collapsible area for a custom report option ↑ -->
        </div> 
<!-- Container for custom report ↑ -->

       <!-- ******************************* -->
<!--       </div> -->

        <div class="container-fluid mx-1" id="reportSection">
<?php
$totalSymbols = "";
$totalMinutes = "";
$totalHours = "";
            
include 'dbconnection.php';

//getting records from WRITTEN
$sql = "SELECT * FROM writtenDB WHERE dateFinished BETWEEN '".$weekStart."' AND '".$weekEndShow."' ORDER BY dateFinished DESC";

//(MySQLi Object-oriented)
$result = $database->query($sql);

if ($result->num_rows > 0) {
    echo '<h2 id="written">Written translations this week</h2>
          <a href="#verbal" class="btn btn-success">Go to verbal part</a>  
    <table class="table table-striped table-light table-hover table-responsive-sm">
        <thead>
            <tr>
              <th scope="col">From</th>
              <th scope="col">Department</th>
              <th scope="col">Done by</th>
              <th scope="col">Document</th>              
              <th scope="col">Started</th>
              <th scope="col">Finished</th>
              <th scope="col">Symbols</th>
            </tr>
        </thead>
        <tbody>';
    
    //total symbols
    $totalSymbols = 0;
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["requesterName"]."</td>
                <td>".$row["requesterDepartment"]."</td>
                <td>".$row["doneBy"]."</td>
                <td>".$row["docTitle"]."</td>                
                <td>".date('F j, Y', strtotime($row["dateStarted"]))."</td>
                <td>".date('F j, Y', strtotime($row["dateFinished"]))."</td>
                <td>".number_format($row["symbols"], 0, ',', ' ')."</td>
              </tr>";
        $totalSymbols += $row["symbols"];
    }
    echo "<tr>
            <td>Total</td>
            <td></td>
            <td></td>
            <td></td>            
            <td></td>
            <td></td>
            <td>".number_format($totalSymbols, 0, ',', ' ')."</td>
          </tr>
            </tbody></table>";
} else {
    echo "<h3 class='text-center my-2'>So far no written translations done this week<hr></h3>";
}
            
            
//getting records from VERBAL translations table
$sql2 = "SELECT * FROM verbalDB WHERE date BETWEEN '".$weekStart."' AND '".$weekEndShow."' ORDER BY date DESC";

//(MySQLi Object-oriented)
$result2 = $database->query($sql2);

if ($result2->num_rows > 0) {
    echo '<h2 id="verbal" style="padding-top: 100px;">Verbal translations this week</h2>
          <a href="#written" class="btn btn-success">Go to written part</a>  
    <table class="table table-striped table-light table-hover table-responsive-sm">
        <thead>
            <tr>
              <th scope="col">From</th>
              <th scope="col">Department</th>
              <th scope="col">Done by</th>
              <th scope="col">Event</th>
              <th scope="col">Date</th>
              <th scope="col">Time started</th>
              <th scope="col">Duration</th>              
            </tr>
        </thead>
        <tbody>';
    
    $totalHours = 0;
    $totalMinutes = 0;
    // output data of each row
    while($row = $result2->fetch_assoc()) {
        echo "<tr>
                <td>".$row["requesterName"]."</td>
                <td>".$row["requesterDepartment"]."</td>
                <td>".$row["doneBy"]."</td>
                <td>".$row["event"]."</td>
                <td>".date('F j, Y', strtotime($row["date"]))."</td>
                <td>".date('H:i', strtotime($row["timeStarted"]))."</td>
                <td>".$row["duration"]."</td>                
              </tr>";
            $totalMinutes += $row["duration"];
    }
    $totalHours = $totalMinutes / 60;
    echo "<tr>
            <td>Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>            
            <td></td>
            <td>".round($totalHours, 1)." hours</td>
          </tr>
            </tbody></table>";
    echo "</tbody></table>";
} else {
    echo "<h3 class='text-center my-2'>So far no verbal translations this week<hr></h3>";
}

            
            
            
//function for cleaning temporary table
            
function truncateTemp(){
    //include 'dbconnection.php';
    
    $sqlClean = "TRUNCATE TABLE temporary";
    global $database;
    $result = mysqli_query($database, $sqlClean);
}
            

            
            
            
            
/**********************************************************/

//function on inserting to report ↓
    function reportInsertion($limit, $from, $by, $nameInsert = "noname"){
        //$limit - the limit of records number after which remained collapsed under Others
        //$from - writtenDB or verbalDB
        //$by - requesterDepartment; requesterName; doneBy
        //$nameInsert - optional argument if any value set - last column "department" in report table will be populated with for sections Written and Verbal translations by Names
        global $database, $weekStart, $weekEnd, $totalSymbols, $totalMinutes;
        
        $sqlcheck = "SELECT * FROM `".$from."`";
        $resultCheck = $database->query($sqlcheck);
        $row = $resultCheck->fetch_assoc();
        
        $whereFilter = "";
        
        //check for type of the table writtenDB or verbalDB. dateFinished contained only in writtenDB ↓
        if(array_key_exists("dateFinished",$row)) {
            //part processing written table ↓
            $sumSelector = "symbols";
            $sql = "SELECT SUM(".$sumSelector."), ".$by." FROM `".$from."` WHERE dateFinished BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY ".$by.""; 
            
            $resultSum = $database->query($sql);


            if($resultSum->num_rows > 0) {
                //while loop for inserting data to temporary database

                while($row = $resultSum->fetch_assoc()) {
                    $percentage = $row['SUM('.$sumSelector.')'] / $totalSymbols * 100;
                    $pages = $row['SUM('.$sumSelector.')'] / 1600;
                    $selector = $row[''.$by.''];
                    $departmentOfRequester = '';
                    
                    //trigger for sections BY NAME ↓
                    if($nameInsert != "noname"){
                        //getting department of a requester
                        $name = $row['requesterName'];
                        $sqlDeptQuery = "SELECT * FROM `".$from."` WHERE requesterName = '".$name."'";
                        $resultDeptQuery = $database->query($sqlDeptQuery);
                        
                        if($resultDeptQuery->num_rows > 0) {
                            while($row3 = $resultDeptQuery->fetch_assoc()) {
                                $departmentOfRequester = $row3['requesterDepartment'];
                            }
                        }
                    }
                    //trigger for sections BY NAME ↑                    
                    
                    
                    $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent, department)
                          VALUES ('".$selector."', '".$row['SUM('.$sumSelector.')']."', '".$pages."', '".$percentage."', '".$departmentOfRequester."')";
                    $result = $database->query($sqlInsert);
                }
            }            
            //part processing written table ↑
        } else {
            //part processing verbal table ↓
            $sumSelector = "duration";
            $sql = "SELECT SUM(".$sumSelector."), ".$by." FROM `".$from."` WHERE date BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY ".$by." ORDER BY duration DESC";
            
            $resultSum = $database->query($sql);
            
            if($resultSum->num_rows > 0) {
                //while loop for inserting data to temporary database
                while($row = $resultSum->fetch_assoc()) {                        
                    $percentage = $row['SUM('.$sumSelector.')'] / $totalMinutes * 100;
                                        
                    $hours = $row['SUM('.$sumSelector.')'] / 60;
                    $selector = $row[''.$by.''];
                    $departmentOfRequester = '';
                    
                    //trigger for sections BY NAME ↓
                    if($nameInsert != "noname"){
                        //getting department of a requester
                        $name = $row['requesterName'];
                        $sqlDeptQuery = "SELECT * FROM `".$from."` WHERE requesterName = '".$name."'";
                        $resultDeptQuery = $database->query($sqlDeptQuery);
                        
                        if($resultDeptQuery->num_rows > 0) {
                            while($row2 = $resultDeptQuery->fetch_assoc()) {
                                $departmentOfRequester = $row2['requesterDepartment'];
                            }
                        }
                    }
                    //trigger for sections BY NAME ↑
                    
                    $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent, department)
                                  VALUES ('".$selector."', '".$row['SUM('.$sumSelector.')']."', '".$hours."', '".$percentage."', '".$departmentOfRequester."')";
                    $result = $database->query($sqlInsert);
                    
                    
                }
            }
            //part processing verbal table ↑
        }
        
        //$resultSum = $database->query($sql);

      /*  
        if($resultSum->num_rows > 0) {
            //while loop for inserting data to temporary database
    
            while($row = $resultSum->fetch_assoc()) {
                $percentage = $row['SUM('.$sumSelector.')'] / $totalSymbols * 100;
                $pages = $row['SUM('.$sumSelector.')'] / 1600;
                $selector = $row[''.$by.''];
                $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent)
                      VALUES ('".$selector."', '".$row['SUM('.$sumSelector.')']."', '".$pages."', '".$percentage."')";
                $result = $database->query($sqlInsert);
            }
        }
        */
        
        //importing from temporary database to the report database ↓

        $sqlImport = "SELECT * FROM `temporary` ORDER BY total DESC";
        $resultImport = $database->query($sqlImport);
        
        
        //fetching all rows from temporary table into associated array ↓
        $dataArray = [];
        while ($row = $resultImport->fetch_assoc()) {
            $dataArray[] = $row;
        }
        $recordsNo = count($dataArray);     
        //length of array from temp table
        
        //fetching all rows from temporary table into associated array ↑
                
        
        //inserting $limit champions ↓
        
        if($resultImport->num_rows > 5) {
            $i = 0;
            while($i<$limit) {
                $selector = $dataArray[$i]["selector"];
                $total = $dataArray[$i]["total"];
                $pageshours = $dataArray[$i]["pageshours"];
                $percent = $dataArray[$i]["percent"];
                $department = $dataArray[$i]["department"];
                
                $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department) VALUES ('".$selector."', '".$total."', '".$pageshours."', '".$percent."', '".$department."')";
                $result = $database->query($sqlInsert);
                
                $i++;
            }
        } elseif($resultImport->num_rows > 0 && $resultImport->num_rows <=5) {
            
            $i = 0;
            while($i<$recordsNo) {
                $selector = $dataArray[$i]["selector"];
                $total = $dataArray[$i]["total"];
                $pageshours = $dataArray[$i]["pageshours"];
                $percent = $dataArray[$i]["percent"];
                $department = $dataArray[$i]["department"];
                
                $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department) VALUES ('".$selector."', '".$total."', '".$pageshours."', '".$percent."', '".$department."')";
                $result = $database->query($sqlInsert);
                
                $i++;
            }            
        } else {
            $messageEmpty = "no records done";
        }
        
        //inserting $limit champions ↑
        
        //inserting Others row ↓

            
        $selectorOthers = "Others";
        
        //counters for others
        $totalOthers = 0;
        $pageshoursOthers = 0;
        $percentOthers = 0;
        $j = $limit;
        
        while($j < $recordsNo){
            $totalOthers += $dataArray[$j]["total"];
            $pageshoursOthers += $dataArray[$j]["pageshours"];
            $percentOthers += $dataArray[$j]["percent"];
            $j++;
        }
        
        $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                          VALUES ('Others', '".$totalOthers."', '".$pageshoursOthers."', '".$percentOthers."', '')";
        $result = $database->query($sqlInsert);
        //inserting Others row ↑
        
        //inserting space row between the sections ↓
        $sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department) VALUES ('', '', '', '', '')";
        
        $database->query($sqlInsertSpace);        
    }
//function on inserting to report ↑
            
            
/**********************************************************/
            
            
            
            
            
            
//cleaning report table from old version
                
    $sqlClean = "TRUNCATE TABLE report";
    $result = mysqli_query($database, $sqlClean);
            
//creating report in table for this week            
//first of all clean all previous data from report database
truncateTemp();
            
                        //--------First part of the report table - W R I T T E N translations:

//inserting description row 
            /* before non-numeric warning it was like: 
            $totalPages = $totalSymbols / 1800; 
            */
$totalPages = '';
if (is_numeric($totalSymbols)){
$totalPages = $totalSymbols / 1600;
}
 
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('Written translations by Departments', '".$totalSymbols."', '".$totalPages."', '100', '')";            

$resultDeptSum2 = $database->query($sqlInsertSpace);
            
            
//clearing temporary database
truncateTemp();
//$sqlClean = "TRUNCATE TABLE temporary";
//$database->query($sqlClean);
            
            
//selecting all written translations for curent week grouped--------- B Y   D E P A R T M E N T
            
reportInsertion(5, "writtenDB", "requesterDepartment");
            
  /*        before implementing function          
$sqlDept = "SELECT SUM(symbols), requesterDepartment FROM `writtenDB` WHERE dateFinished BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY requesterDepartment";

$resultDeptSum = $database->query($sqlDept);         
            
if($resultDeptSum->num_rows > 0) {
    
//while loop for inserting data to temporary database
    while($row = $resultDeptSum->fetch_assoc()) {
        $percentage = $row['SUM(symbols)'] / $totalSymbols * 100;
        $pages = $row['SUM(symbols)'] / 1600;
        $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent)
                      VALUES ('".$row['requesterDepartment']."', '".$row['SUM(symbols)']."', '".$pages."', '".$percentage."')";
        $result = $database->query($sqlInsert);
    }
}    
    
    
//importing from temporary database to the report database    

    $sqlImport = "SELECT * FROM `temporary` ORDER BY total DESC";
    $resultImport = $database->query($sqlImport);
            
//fetching all rows from temporary table into associated array            
    $dataArray = [];
    while ($row = $resultImport->fetch_assoc()) {
    $dataArray[] = $row;
    }
//fetching all rows from temporary table into associated array
    
//inserting 3 champions             
    if($resultImport->num_rows > 0) {
    $i = 0;
    while($i < 3) {
        $selector = $dataArray[$i]["selector"];
        $total = $dataArray[$i]["total"];
        $pageshours = $dataArray[$i]["pageshours"];
        $percent = $dataArray[$i]["percent"];
        
        $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                          VALUES ('".$selector."', '".$total."', '".$pageshours."', '".$percent."', '".$percent."')";
            $result = $database->query($sqlInsert);
        
        $i++;
    }
    }
//inserting 3 champions 

//inserting Others row            
    $recordsNo = count($dataArray);     //length of array from temp table
            
    $selectorOthers = "Others";
//counters for others            
    $totalOthers = 0;
    $pageshoursOthers = 0;
    $percentOthers = 0;
    $j = 3;
    while($j < $recordsNo){
        $totalOthers += $dataArray[$j]["total"];
        $pageshoursOthers += $dataArray[$j]["pageshours"];
        $percentOthers += $dataArray[$j]["percent"];     
        $j++;
    }
        $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                          VALUES ('Others', '".$totalOthers."', '".$pageshoursOthers."', '".$percentOthers."', '".$percent."')";
        $result = $database->query($sqlInsert);
//inserting Others row            
            /*
    if($resultImport->num_rows > 0) {
    
//while loop for inserting data to report database
        while($row = $resultImport->fetch_assoc()) {        
            $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                          VALUES ('".$row['selector']."', '".$row['total']."', '".$row['pageshours']."', '".$row['percent']."', '".$row['department']."')";
            $result = $database->query($sqlInsert);
        }
    } */

            
            
//selecting all written translations for curent week grouped ---------B Y    N A M E S
//inserting space row between written by department and by name

//inserting description row between written by department and by name
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('Written translations by Names', '', '', '', '')";
$resultDeptSum = $database->query($sqlInsertSpace);

//clearing temporary database
truncateTemp();                        
reportInsertion(5, "writtenDB", "requesterName", "name");
 
            
            
            
/*
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('', '', '', '', '')";
$database->query($sqlInsertSpace);
            
            
//clearing temporary database
truncateTemp();
//$sqlClean = "TRUNCATE TABLE temporary";
//$database->query($sqlClean);
            
$sqlDept = "SELECT SUM(symbols), requesterName FROM `writtenDB` WHERE dateFinished BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY requesterName";

$resultDeptSum = $database->query($sqlDept);

if($resultDeptSum->num_rows > 0) {
    
//while loop for inserting data to report database
    while($row = $resultDeptSum->fetch_assoc()) {
        $percentage = $row['SUM(symbols)'] / $totalSymbols * 100;
        $pages = $row['SUM(symbols)'] / 1600;
        $symbols = $row['SUM(symbols)'];
        
        //getting department of a requester
        $name = $row['requesterName'];
        $departmentOfRequester = '';
        $sqlDeptQuery = "SELECT * FROM writtenDB WHERE requesterName = '".$name."'";
        $resultDeptQuery = $database->query($sqlDeptQuery);
        if($resultDeptQuery->num_rows > 0) {
            while($row = $resultDeptQuery->fetch_assoc()) {
                $departmentOfRequester = $row['requesterDepartment'];
            }
        }
        //getting department of a requester
        
        
        $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent, department)
                      VALUES ('".$name."', '".$symbols."', '".$pages."', '".$percentage."', '".$departmentOfRequester."')";
        $result = $database->query($sqlInsert);
    }
}
            
//importing from temporary database to the report database    

    $sqlImport = "SELECT * FROM `temporary` ORDER BY total DESC";
    $resultImport = $database->query($sqlImport);
    
    if($resultImport->num_rows > 0) {
    
//while loop for inserting data to report database
        while($row = $resultImport->fetch_assoc()) {        
            $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                          VALUES ('".$row['selector']."', '".$row['total']."', '".$row['pageshours']."', '".$row['percent']."', '".$row['department']."')";
            $result = $database->query($sqlInsert);
        }
    }
            */
            
            
                    //--------Second part of the report table - V E R B A L translations:

//inserting description row between written and verbal translations
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('Verbal translations by Departments', '".$totalMinutes."', '".$totalHours."', '100', '')";
$resultDeptSum = $database->query($sqlInsertSpace);
            
            
            /*
//inserting space row between written and verbal
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('', '', '', '', '')";

$resultDeptSum = $database->query($sqlInsertSpace);
            */

            

//clearing temporary database
truncateTemp();            
//$sqlClean = "TRUNCATE TABLE temporary";
//$database->query($sqlClean);
 
            
//selecting all verbal translations for curent week grouped--------- B Y   D E P A R T M E N T
            
reportInsertion(5, "verbalDB", "requesterDepartment");            
            
            
            /*
$sqlDept = "SELECT SUM(duration), requesterDepartment FROM `verbalDB` WHERE date BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY requesterDepartment ORDER BY duration DESC";

$resultDeptSum = $database->query($sqlDept);

if($resultDeptSum->num_rows > 0) {
    
//while loop for inserting data to report database
    while($row = $resultDeptSum->fetch_assoc()) {        
        $percentage = $row['SUM(duration)'] / $totalMinutes * 100;
        $hours = $row['SUM(duration)'] / 60;
        $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent)
                      VALUES ('".$row['requesterDepartment']."', '".$row['SUM(duration)']."', '".$hours."', '".$percentage."')";
        $result = $database->query($sqlInsert);
    }
}            
            
//importing from temporary database to the report database    

    $sqlImport = "SELECT * FROM `temporary` ORDER BY total DESC";
    $resultImport = $database->query($sqlImport);
    
    if($resultImport->num_rows > 0) {
    
//while loop for inserting data to report database
    while($row = $resultImport->fetch_assoc()) {        
        $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('".$row['selector']."', '".$row['total']."', '".$row['pageshours']."', '".$row['percent']."', '".$row['department']."')";
        $result = $database->query($sqlInsert);
    }
    }

*/

//selecting all verbal translations for curent week grouped ---------B Y    N A M E S
            
//inserting description row between verbal by department and by name
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('Verbal translations by Names', '', '', '', '')";
$resultDeptSum = $database->query($sqlInsertSpace);
            
//clearing temporary database
truncateTemp();
//$sqlClean = "TRUNCATE TABLE temporary";
//$database->query($sqlClean);     
            
reportInsertion(5, "verbalDB", "requesterName", "name");                

            
            
            
            
            
            
            /*
//inserting space row between verbal by department and by names
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('', '', '', '', '')";
$database->query($sqlInsertSpace);
            
            
$sqlDept = "SELECT SUM(duration), requesterName FROM `verbalDB` WHERE date BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY requesterName ORDER BY duration DESC";

$resultDeptSum = $database->query($sqlDept);

if($resultDeptSum->num_rows > 0) {
    
//while loop for inserting data to report database
    while($row = $resultDeptSum->fetch_assoc()) {
        $percentage = $row['SUM(duration)'] / $totalMinutes * 100;
        $hours = $row['SUM(duration)'] / 60;
        $minutes = $row['SUM(duration)'];
        
        //getting department of a requester
        $name = $row['requesterName'];
        $departmentOfRequester = '';
        $sqlDeptQuery = "SELECT * FROM verbalDB WHERE requesterName = '".$name."'";
        $resultDeptQuery = $database->query($sqlDeptQuery);
        if($resultDeptQuery->num_rows > 0) {
            while($row = $resultDeptQuery->fetch_assoc()) {
                $departmentOfRequester = $row['requesterDepartment'];
            }
        }
        //getting department of a requester
        
        
        $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent, department)
                      VALUES ('".$name."', '".$minutes."', '".$hours."', '".$percentage."', '".$departmentOfRequester."')";
        $result = $database->query($sqlInsert);
    }
}
            
//importing from temporary database to the report database    

    $sqlImport = "SELECT * FROM `temporary` ORDER BY total DESC";
    $resultImport = $database->query($sqlImport);
    
    if($resultImport->num_rows > 0) {
    
//while loop for inserting data to report database
        while($row = $resultImport->fetch_assoc()) {        
            $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                          VALUES ('".$row['selector']."', '".$row['total']."', '".$row['pageshours']."', '".$row['percent']."', '".$row['department']."')";
            $result = $database->query($sqlInsert);
        }
    }            

*/

                    //--------Third part of the report table - W R I T T E N translations by Translators
//inserting space row
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('', '', '', '', '')";
$resultDeptSum = $database->query($sqlInsertSpace);
            
//inserting description row 
            /* before non-numeric warning it was like: 
            $totalPages = $totalSymbols / 1800; 
            */
if (is_numeric($totalSymbols)){
$totalPages = $totalSymbols / 1600;
}
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('Written translations by Translators', '".$totalSymbols."', '".$totalPages."', '100', '')";
$resultDeptSum = $database->query($sqlInsertSpace);
            
//clearing temporary database
truncateTemp();            
//$sqlClean = "TRUNCATE TABLE temporary";
//$database->query($sqlClean);
            
//selecting all written translations for curent week grouped--------- B Y   T R A N S L A T O R S
$sqlDept = "SELECT SUM(symbols), doneBy FROM `writtenDB` WHERE dateFinished BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY doneBy";

$resultDeptSum = $database->query($sqlDept);

if($resultDeptSum->num_rows > 0) {
    
//while loop for inserting data to report database
    while($row = $resultDeptSum->fetch_assoc()) {
        $percentage = $row['SUM(symbols)'] / $totalSymbols * 100;
        $pages = $row['SUM(symbols)'] / 1600;
        $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent)
                      VALUES ('".$row['doneBy']."', '".$row['SUM(symbols)']."', '".$pages."', '".$percentage."')";
        $result = $database->query($sqlInsert);
    } 
}
            
//importing from temporary database to the report database    

    $sqlImport = "SELECT * FROM `temporary` ORDER BY total DESC";
    $resultImport = $database->query($sqlImport);
    
    if($resultImport->num_rows > 0) {
    
//while loop for inserting data to report database
        while($row = $resultImport->fetch_assoc()) {        
            $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                          VALUES ('".$row['selector']."', '".$row['total']."', '".$row['pageshours']."', '".$row['percent']."', '".$row['department']."')";
            $result = $database->query($sqlInsert);
        }
    }            
            
            

                    //--------Fourth part of the report table - V E R B A L translations by Translators
//inserting space row between written and verbal
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('', '', '', '', '')";

$resultDeptSum = $database->query($sqlInsertSpace);
            
//inserting description row between written and verbal translations
$sqlInsertSpace = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('Verbal translations by Translators', '".$totalMinutes."', '".$totalHours."', '100', '')";

$resultDeptSum = $database->query($sqlInsertSpace);            

//clearing temporary database
truncateTemp();
//$sqlClean = "TRUNCATE TABLE temporary";
//$database->query($sqlClean);
 
            
//selecting all verbal translations for curent week grouped--------- B Y   T R A N S L A T O R S
$sqlDept = "SELECT SUM(duration), doneBy FROM `verbalDB` WHERE date BETWEEN '".$weekStart."' AND '".$weekEnd."' GROUP BY doneBy";

$resultDeptSum = $database->query($sqlDept);

if($resultDeptSum->num_rows > 0) {
    
//while loop for inserting data to report database
    while($row = $resultDeptSum->fetch_assoc()) {        
        $percentage = $row['SUM(duration)'] / $totalMinutes * 100;
        $hours = $row['SUM(duration)'] / 60;
        $sqlInsert = "INSERT INTO temporary (selector, total, pageshours, percent)
                      VALUES ('".$row['doneBy']."', '".$row['SUM(duration)']."', '".$hours."', '".$percentage."')";
        $result = $database->query($sqlInsert);
    }
}
            
            
//importing from temporary database to the report database    

    $sqlImport = "SELECT * FROM `temporary` ORDER BY total DESC";
    $resultImport = $database->query($sqlImport);
    
    if($resultImport->num_rows > 0) {
    
//while loop for inserting data to report database
    while($row = $resultImport->fetch_assoc()) {        
        $sqlInsert = "INSERT INTO report (selector, total, pageshours, percent, department)
                      VALUES ('".$row['selector']."', '".$row['total']."', '".$row['pageshours']."', '".$row['percent']."', '".$row['department']."')";
        $result = $database->query($sqlInsert);
    }
    }
        
?>
    </div>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>   
    <script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    
    <script>
$(document).ready(function(){
    // Offset for navbar
      var navHeight = $("#navbarId").height() + 18;
      $("#afterNav").css("marginTop", navHeight);    
    //Setting body padding-bottom equals to footer height
        var footerHeight = $("#footerwrap").outerHeight();
        $("body").css("padding-bottom", footerHeight);   
    

    
function getInternetExplorerVersion()
    {
        var rv = -1;
        if (navigator.appName == 'Microsoft Internet Explorer')
        {
            var ua = navigator.userAgent;
            var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null)
                rv = parseFloat( RegExp.$1 );
        }
        else if (navigator.appName == 'Netscape')
        {
            var ua = navigator.userAgent;
            var re  = new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})");
            if (re.exec(ua) != null)
                rv = parseFloat( RegExp.$1 );
        }
        return rv;
    }
if(getInternetExplorerVersion()!==-1){
    //applying datepicker from jQueryUI for IE
     $( function() {
        $( "#dateStarted" ).datepicker();
      } );  
      $( function() {
        $( "#dateFinished" ).datepicker();
      } ); 	
} else {	
    //applying default value(today) for date inputs of non IE browsers
    document.getElementById('dateStarted').valueAsDate = new Date();
    document.getElementById('dateFinished').valueAsDate = new Date();
}
    
    
});
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>