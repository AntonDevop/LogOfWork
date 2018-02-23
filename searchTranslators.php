<?php
    include 'dbconnection.php';
//checking was data recived from ajax function or not
if(isset($_POST["query"])) {
    $output = '';
//making query to search all rows from database
    $query = "SELECT * FROM usersTable WHERE name LIKE '%".$_POST['query']."%'";
//executing query and store result 
    $result = mysqli_query($database, $query);
//creating unordered list in $output to display results
    $output = '<ul class="list-group">';
//if something found from clients database do the following
    if(mysqli_num_rows($result) > 0) {
//while loop for storing recived data from database in associative array called $row
        while($row = mysqli_fetch_array($result)) {
            $output .= '<li>'.$row["name"].'</li>';
        }
    } else {
        $output .='<div class="alert alert-danger d-flex justify-content-center align-items-center" role="alert">
        Name not Found
        </div>';
    }
//closing ul tag in output variable
    $output .= '</ul>';
//returning found data back to ajax result
    echo $output;
    
    
}


?>