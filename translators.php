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


//----Processing forms
if($_SERVER["REQUEST_METHOD"] == "POST") {
include 'dbconnection.php';
    
//----Deleting a user    
    if(isset($_POST["delete"]) && isset($_POST["nameDelete"])) {
        $name = $_POST["nameDelete"];
        
        if($name == $translatorName) {
            echo "<script>alert('Error! You cannot delete yourself! Please stop fooling around!');</script>";
        } else {
    
            $sql = "DELETE FROM usersTable WHERE name='".$name."'";
            if (mysqli_query($database, $sql)) {
                echo "<script> alert('User deleted successfully');</script>";
                } else {
            echo "<script> alert('Error deleting record:'". $conn->error ."';</script>";
            }
        }
    } else { 
//----Adding a new user  
        $name = $_POST["fullName"];
        $email= $_POST["email"];
        $password = $_POST["password"];
        $passwordHashed = password_hash($password,PASSWORD_DEFAULT);
        $shared = $_POST["emailShared"];
        $location = $_POST["location"];
        $company = $_POST["company"];
        $phone = $_POST["phone"];
        $mobile = $_POST["mobile"];
        $badge = $_POST["badge"];
        if(isset($_POST["manager"]) && $_POST["manager"] == "yes") {
            $manager = "yes";
        } else {
            $manager = "no";
        }
        
        
        $sql = "INSERT INTO usersTable (name, email, password, manager, sharedEmail, company, location, phone, mobile, badge)
        VALUES ('".mysqli_real_escape_string($database, $name)."', '".mysqli_real_escape_string($database, $email)."', '".$passwordHashed."', '".mysqli_real_escape_string($database, $manager)."', '".mysqli_real_escape_string($database, $shared)."', '".mysqli_real_escape_string($database, $company)."', '".mysqli_real_escape_string($database, $location)."', '".mysqli_real_escape_string($database, $phone)."', '".mysqli_real_escape_string($database, $mobile)."', '".mysqli_real_escape_string($database, $badge)."')";
        
        //inserting row with given data
        $result = $database->query($sql);
        
        $query = "SELECT * FROM usersTable WHERE name = '".$translatorName."'";
        $result = mysqli_query($database, $query);
        
        $row = mysqli_fetch_array($result);
        $by = $row['email'];
                
        $to = filter_var($email, FILTER_VALIDATE_EMAIL);
        $subject = "Your account in LogOf.Work created";
        $msg = "Dear ".$name."! \n \n You have been successfully signed up for LogOf.Work system by ".$row['name'].". \n To log in please click a link below and use your email address and this password: ". $password ." 
        \n
        The link: https://logof.work
        \n
        For security reasons please change the password with your own after first log in.\n
        Please do not reply to this email it was created automatically.\n
        \n
        Sincerely yours,
        Lonely Log Of Work Robot.
        ";
        $headers = "From: newuser@LogOf.Work"."\r\n"."CC: ".$row['email']."";
        mail($to, $subject, $msg, $headers);
        
        $alertMsg = "<script>alert('You have successfully created user with name: '".$name."'. Confirmation email with details was sent to '".$email."' and you in copy');</script>";
        echo "<script>alert('You have successfully created user with name: '".$name."'. Confirmation email with details was sent to '".$email."' and you in copy');</script>";
    }    
}
?>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

    <title>Translators | LogOfWork</title>
        
    <link rel="stylesheet" href="css/main.css">
    
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Amaranth:700i" rel="stylesheet">
    
    <?php include 'metrics.php'; ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top mb-5 justify-content-between" id="navbarId">
        <img id="logo" src="img/logoNav.png" alt="logo picture">
        <h2 class="yellowstrocked">Translators</h2>
        <div>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
               <?php 
                    if (array_key_exists("manager", $_SESSION)) {
                        echo '
                <a class="nav-item nav-link btn btn-info mx-1 px-2" href="manager.php">Manager page <span class="sr-only">(manager)</span></a>
                <a class="nav-item nav-link btn btn-warning mx-1 px-2" href="translators.php">Translators</a>
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
    
    
    <div class="container-fluid mt-5 pt-5" id="afterNav">
        <p><a class="btn btn-success" data-toggle="collapse" href="#addNew" role="button" aria-expanded="false" aria-controls="addNew">Add a new user</a>
        <a class="btn btn-danger" data-toggle="collapse" href="#delete" role="button" aria-expanded="false" aria-controls="delete">Delete a user</a></p>
        
       <div class="collapse formbackground" id="addNew">
       <h3>Adding a new translator to the database</h3>
       
        <form method="post" name="addNew" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="row">

                <div class="col-md">
                    <div class="form-group">
                        <label for="fullName">Full name <span class="badge badge-warning">Required</span></label>
                        <input type="text" class="form-control" id="fullName" aria-describedby="fullName" placeholder="For example: Doe John" name="fullName" required>
                        <small id="emailHelp" class="form-text text-muted">Enter Last Name then First name.</small>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="email">Email address <span class="badge badge-warning">Required</span></label>
                        <input type="email" class="form-control" id="email" aria-describedby="email" placeholder="Enter email" name="email" required>
                        <small id="emailHelp" class="form-text text-muted">Email will never be shared with anyone else.</small>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="password">Password <span class="badge badge-warning">Required</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <small id="password" class="form-text text-muted">Later user can change it.</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for="emailShared">Shared Email</label>
                        <input type="email" class="form-control" id="emailShared" aria-describedby="emailShared" placeholder="Enter shared email" name="emailShared">
                        <small id="emailHelp" class="form-text text-muted">Enter shared email if available.</small>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" aria-describedby="location" placeholder="For example: B1, 31K">
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" class="form-control" id="company" name="company" aria-describedby="company" placeholder="Choose or enter a parent company" value="<?php echo $row['company']; ?>" list="suggests">
                            <datalist id="suggests">
                                <option value="TCO">
                                <option value="Bolashak">
                                <option value="Career Holdings">
                                <option value="Fircroft">
                                <option value="KPJV">
                                <option value="Fuor">
                                <option value="WorleyParsons">
                                <option value="KGNT">
                                <option value="KING">
                            </datalist>                                
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" aria-describedby="phoneNumber" placeholder="Enter phone number">
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="text" class="form-control" id="mobile" aria-describedby="mobileNumber" placeholder="Enter mobile phone number" name="mobile">
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="badge">Badge</label>
                        <input type="number" class="form-control" id="badge" aria-describedby="badgeNumber" name="badge" placeholder="Enter badge number">
                        <input type="hidden" name="addingNewUser">
                    </div>
                </div>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="manager" name="manager" value="yes">
                <label class="form-check-label" for="manager">Check if manager rights for work log is required</label>
            </div>
            <button type="submit" id="create" name="export" class="btn btn-success mt-3">Create!</button>
        </form>
        
        
       </div>
       
       <div class="collapse" id="delete">
       <h3>Delete a translator from the database</h3>
            <form class="form-inline" method="post" id="deleteForm" name="deleteForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

                <div class="form-group">
                    <label for="nameDelete" class="pr-3">Name </label>
                    <input type="text" class="form-control" id="nameDelete" aria-describedby="nameDelete" placeholder="Enter first or last name" name="nameDelete" required autocomplete="off">
                    <input type="hidden" name="delete"> 
                </div>

                <button type="submit" id="deleteButton" name="deleteButton" class="btn btn-danger ml-3">Delete</button>
            </form>
                    <div id="autocompleteBox">
                    </div>                    
        
        
       </div>
       
       </div>
        <div class="container-fluid mx-1" id="reportSection">
            <h2>Translators</h2>
<?php
$translatorsCounter = 1;        
include 'dbconnection.php';

//getting records from WRITTEN
$sql = "SELECT * FROM usersTable ORDER BY name ASC";

//(MySQLi Object-oriented)
$result = $database->query($sql);

if ($result->num_rows > 0) {
    echo '
    <table class="table table-striped table-light table-hover table-responsive-sm">
        <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">Full name</th>
              <th scope="col">Email</th>
              <th scope="col">Phone</th>
              <th scope="col">Mobile</th>              
              <th scope="col">Location</th>
              <th scope="col">Shared email</th>
              <th scope="col">Company</th>
              <th scope="col">Badge</th>
              <th scope="col">Manager</th>
            </tr>
        </thead>
        <tbody>';
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$translatorsCounter++."</td>
                <td>".$row["name"]."</td>
                <td>".$row["email"]."</td>
                <td>".$row["phone"]."</td>
                <td>".$row["mobile"]."</td>
                <td>".$row["location"]."</td>
                <td>".$row["sharedEmail"]."</td>
                <td>".$row["company"]."</td>
                <td>".$row["badge"]."</td>
                <td>".$row["manager"]."</td>
              </tr>";
    }
    echo "
     </tbody></table>
    ";
}
    
?>
    </div>
    <?php include 'footer.php'; ?>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>   
        <script>
//starting jquery code for textbox autocomplete
    $(document).ready(function(){
        $("#nameDelete").keyup(function(){
            var query = $(this).val();
            if(query != '' && query.length > 2) {
//calling for ajax function
                $.ajax({
                    url:"searchTranslators.php",
                    method:"POST",
                    data: {query:query},
//function to be called if request succeed
//(data) here means information we recieved from server 
                    success:function(data){
//animation for autocompleteBox to appear in html
                        $("#autocompleteBox").fadeIn()
//displaying recieved data in html
                        $("#autocompleteBox").html(data);
                    }
                    
                });
            }
        });
//detecting list item by clicking on it
        $(document).on("click", "li", function(){
//assigning full name to textbox of input
             $("#nameDelete").val($(this).text());
//hiding suggestion div
             $("#autocompleteBox").fadeOut();
        });
        
    //Setting body padding-bottom equals to footer height
        var footerHeight = $("#footerwrap").outerHeight();
        $("body").css("padding-bottom", footerHeight);     
    });
            
            
//js confirm for deleting
$('#deleteForm').submit(function(event){
     if(!confirm("Please confirm deleting the following user from the database: " + $("#nameDelete").val())){
        event.preventDefault();
      }
    });
            
    
    // Offset for navbar
        var navHeight = $("#navbarId").height() + 18;
        $("#afterNav").css("marginTop", navHeight);


    </script>
</body>

</html>