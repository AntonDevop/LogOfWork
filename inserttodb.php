<?php
session_start();

if (array_key_exists("name", $_COOKIE)) {    
    $_SESSION['name'] = $_COOKIE['name'];
}
if (array_key_exists("name", $_SESSION)) {
  
    $doneBy = $_SESSION["name"];
    
}



if($_SERVER["REQUEST_METHOD"] == "POST") { 
        include 'dbconnection.php';
    
    $requester = $_POST["requester"];
    //splitting requestor string to array for [0]=name [1]=department
    $requesterArray = explode(". From: ", $requester);
    $reqFullName = $requesterArray[0];
    $reqDepartment = $requesterArray[1]; 
    
    

    if($_POST["typeOfTranslation"] == "written") {  //FOR WRITTEN
    
        $docTitle = $_POST["docTitle"];
        $symbols = $_POST["symbols"];
        //$dateStarted = $_POST["dateStarted"];
      	$dateStarted = date('y-m-d',strtotime($_POST["dateStarted"]));        
      	$dateFinished = date('y-m-d',strtotime($_POST["dateFinished"]));
        
        $sql = "INSERT INTO writtenDB (requesterName, requesterDepartment, docTitle, symbols, dateStarted, dateFinished, doneBy) VALUES ('".mysqli_real_escape_string($database, $reqFullName)."', '".mysqli_real_escape_string($database, $reqDepartment)."', '".mysqli_real_escape_string($database, $docTitle)."', '".mysqli_real_escape_string($database, $symbols)."', '".mysqli_real_escape_string($database, $dateStarted)."', '".mysqli_real_escape_string($database, $dateFinished)."', '".mysqli_real_escape_string($database, $doneBy)."')";
        
        if (mysqli_query($database, $sql)) {
            header("Location: main.php");
            echo "ok";
            
            } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($database);
            }

    } else {    //FOR VERBAL
        
        $eventName = $_POST["eventName"];        
        $date = date('y-m-d',strtotime($_POST["date"]));
        $time2 = $_POST["time"];
        $time = date('H:i',strtotime($_POST["time"]));
        $duration = $_POST["duration"];

        $sql = "INSERT INTO verbalDB (requesterName, requesterDepartment, event, date, timeStarted, duration, doneBy) VALUES ('".mysqli_real_escape_string($database, $reqFullName)."', '".mysqli_real_escape_string($database, $reqDepartment)."', '".mysqli_real_escape_string($database, $eventName)."', '".mysqli_real_escape_string($database, $date)."', '".mysqli_real_escape_string($database, $time)."', '".mysqli_real_escape_string($database, $duration)."', '".mysqli_real_escape_string($database, $doneBy)."')";
        
        if (mysqli_query($database, $sql)) {
            header("Location: main.php");
            echo "ok";
            
            } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($database);
            }
        
    }

}

?>