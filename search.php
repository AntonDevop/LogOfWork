<?php
/*database connection
//to be changed with host change
$connect = mysqli_connect("shareddb1e.hosting.stackcp.net", "userstrs-3233a303", "translators910", "userstrs-3233a303");*/
include 'dbconnection.php';
//checking was data recived from ajax function or not
if(isset($_POST["query"])) {
    $output = '';
//making query to search all rows from database
    $query = "SELECT * FROM clients3 WHERE Name LIKE '%".$_POST['query']."%'";
//executing query and store result 
    $result = mysqli_query($database, $query);
//creating unordered list in $output to display results
    $output = '<ul class="list-group">';
//if something found from clients database do the following
    if(mysqli_num_rows($result) > 0) {
//while loop for storing recived data from database in associative array called $row
        while($row = mysqli_fetch_array($result)) {
            $output .= '<li>'.$row["Name"].'. From: '.$row["Department"].'</li>';
        }
    } else {
        $output .='
        <div class="alert alert-danger d-flex justify-content-center align-items-center" role="alert">
        Name not Found
        </div>
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#addRequesterModal">
            Add a new requester
        </button>
        ';
    }
//closing ul tag in output variable
    $output .= '</ul>';
//returning found data back to ajax result
    echo $output;
    
    
}


?>