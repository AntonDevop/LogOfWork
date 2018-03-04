<?php
//database connection
include 'dbconnection.php';
//checking was data recived from ajax function or not

if(isset($_POST["arrayRec"])) {
    $output = $_POST["arrayRec"];
    $rowArray = $_POST["arrayRec"];
    
//fetching to variables general info from row ↓
$translationType = $rowArray["type"];
$by = $rowArray["by"];
$from = $rowArray["from"];
//fetching to variables general info from row ↑

//splitting code by type of translation ↓
if($translationType == "written"){
    
    $doc = $rowArray["doc"];
    $symbols = $rowArray["symbols"];
    $start = date('y-m-d', strtotime($rowArray["start"]));
    $finish = date('y-m-d', strtotime($rowArray["finish"]));
    
    
    
    $sql = "DELETE FROM writtenDB
            WHERE 
            requesterName = '".$from."' AND
            docTitle =  '".$doc."' AND
            symbols =  '".$symbols."' AND
            dateStarted = '".$start."' AND
            dateFinished =  '".$finish."' AND
            doneBy =  '".$by."'
            ";
    $result = mysqli_query($database, $sql);
    
} else {
    $event = $rowArray["event"];
    $date = date('y-m-d', strtotime($rowArray["date"]));
    $time = date('H:i',strtotime($rowArray["time"]));
    $duration = $rowArray["duration"];  
    
    $sql = "DELETE FROM verbalDB
            WHERE 
            requesterName = '".$from."' AND
            event =  '".$event."' AND
            duration =  '".$duration."' AND
            date = '".$date."' AND
            timeStarted =  '".$time."' AND
            doneBy =  '".$by."' LIMIT 1
            ";
    $result = mysqli_query($database, $sql);
    
}
//splitting code by type of translation ↑
    
    
//returning found data back to ajax result
    print_r($output); 
    echo "<br> 1) type ".$translationType."<br> by ".$by."<br> from ".$from."<br>";
    
}


?>