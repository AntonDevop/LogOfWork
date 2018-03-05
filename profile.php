<?php

session_start();

if (array_key_exists("name", $_COOKIE)) {
    
    $_SESSION['name'] = $_COOKIE['name'];

}

if (array_key_exists("name", $_SESSION)) {
  
    $translatorName = $_SESSION["name"];
    
} else {
    
    header("Location: index.php");    
}

//setting manager session from cookie
if (array_key_exists("manager", $_COOKIE)) {
    
    $_SESSION['manager'] = $_COOKIE['manager'];

}

?>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="css/jquery-ui.css">

    <title>My profile | LogOfWork</title>

    <link rel="stylesheet" href="css/main.css">
    
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Amaranth:700i" rel="stylesheet">    
  
    <?php include 'metrics.php'; ?>
</head>

<body>
    <div class="conrainer px-5">
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top mb-5 justify-content-between" id="navbarId">
        <img id="logo" src="img/logoNav.png" alt="logo picture">
        <h2 class="yellowstrocked">Profile</h2>
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
                
                <a class="nav-item nav-link btn btn-warning mx-1 px-2" href="profile.php">My profile</a>    
                                
                <a class="nav-item nav-link btn btn-danger mx-1 px-2" href="logout.php">Log out</a>
                
            </div>
            </div>
        </div>
    </nav>            

       <div class="row pt-5 mt-5" id="afterNav">
           <h1>Your profile data:</h1>
        <?php 
           include 'dbconnection.php';          
           $sql = "SELECT * FROM usersTable WHERE name = '".$translatorName."' LIMIT 1";
           $result = $database->query($sql);
           $row = $result->fetch_assoc();
           
    if (isset($_POST["paswordChangeTrigger"])) {
        
        $passwordCurrent = password_verify($_POST["passwordCurrent"], $row["password"]);
        $passwordNew = password_hash($_POST["passwordNew"],PASSWORD_DEFAULT);
        
        if($passwordCurrent) {
            if($_POST["passwordNew"] != $_POST["passwordConfirm"]){
                echo '<script>alert("New password does not match with confirmation, try again");</script>'; 
            } else {
                $sql = 'UPDATE usersTable 
                SET
                    password="'.$passwordNew.'"
                WHERE 
                    name="'.$row['name'].'" LIMIT 1';
                
            if ($database->query($sql) === TRUE) {
                echo "<script> 
                alert('You successfully changed your password');
                </script>";                
            } else {
                echo "<script> alert('Error changing password');</script>";
            }                
            }
        } else {
            echo '<script>alert("You entered wrong current password, try again");</script>';
            
            
        }
        
        
    } else {          //-----------UPDATE section
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $name = $_POST["fullName"];
            
            if($name != $translatorName){
                /* Back up option 
                //
                $sqlUpdate = "UPDATE `verbalDB`, `writtenDB`
                        SET `verbalDB`.`doneBy` = '".$name."',
                            `writtenDB`.`doneBy` ='".$name."'
                        WHERE `items`.`doneBy` = '".$translatorName."'";
                        
                        $resultUpdateTables = $database->query($sqlUpdate);
                
                
                */
                $sqlUpdateVerbal = "UPDATE `verbalDB`
                        SET `doneBy` = '".$name."'                            
                        WHERE `doneBy` = '".$translatorName."'";
                
                $sqlUpdateWritten = "UPDATE `writtenDB`
                        SET `doneBy` = '".$name."'
                        WHERE `doneBy` = '".$translatorName."'";
                
                $resultUpdateTables = $database->query($sqlUpdateVerbal);
                $resultUpdateTables2 = $database->query($sqlUpdateWritten);
                
                
                
                
                //updating cookie if it was set with new name
                if(array_key_exists("name", $_COOKIE)) {
                    setcookie("name", $name, time()+ 60*60*24*365);
                    $_SESSION['name'] = $_COOKIE['name'];
                    $translatorName = $_SESSION['name'];
                } else {
                    //if cookie is not set update session name
                    $_SESSION['name'] = $name;
                    $translatorName = $_SESSION['name'];
                }
                
            }
            
            $email = $_POST["email"];
            $sharedEmail = $_POST["emailShared"];
            $company = $_POST["company"];
            $location = $_POST["location"];
            $phone = $_POST["phone"];
            $mobile = $_POST["mobile"];
            $badge = $_POST["badge"];

            $sql = "UPDATE usersTable
                SET 
                    name='".$name."',
                    email='".$email."',
                    sharedEmail='".$sharedEmail."',
                    company='".$company."',
                    location='".$location."',
                    phone='".$phone."',
                    mobile='".$mobile."',
                    badge='".$badge."'
                WHERE 
                    name='".$row['name']."'";

            if ($database->query($sql) === TRUE) {
                echo "<script> 
                alert('You successfully changed your profile info');
                </script>";                
            } else {
                echo "<script> alert('Error updating')</script>";
            }

        }
    }
           
           $sql = "SELECT * FROM usersTable WHERE name = '".$translatorName."' LIMIT 1";
           $result = $database->query($sql);
           $row = $result->fetch_assoc();
           echo '
           <table class="table table-striped table-light table-hover">
                <tr>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>                  
                </tr>
                <tr>
                  <td>'.$row["name"].'</th>
                  <td>'.$row["email"].'</th>                  
                </tr>
                
                <tr>
                  <th scope="col">Phone</th>
                  <th scope="col">Mobile</th>
                </tr>
                <tr>
                  <td>'.$row["phone"].'</th>
                  <td>'.$row["mobile"].'</th>
                </tr>

                <tr>
                  <th scope="col">Shared email</th>
                  <th scope="col">Company</th>
                </tr>
                <tr>
                  <td>'.$row["sharedEmail"].'</th>
                  <td>'.$row["company"].'</th>
                </tr>
                <tr>
                  <th scope="col">Location</th>
                  <th scope="col">Badge</th>
                </tr>
                <tr>
                  <td>'.$row["location"].'</th>
                  <td>'.$row["badge"].'</th>
                </tr>                
            </table>
           '; 
           

        ?>
       </div>
       
       
       <!-- Changing profile info -->
           <div class="row"><a class="btn btn-success" data-toggle="collapse" href="#editProfile" role="button" aria-expanded="false" aria-controls="editProfile" style="width: 150px;" id="editButton">Edit my data</a></div>
           <div class="row"><a class="btn btn-primary my-3" data-toggle="collapse" href="#changePassword" role="button" aria-expanded="false" aria-controls="changePassword" style="width: 150px;" id="changeButton">Change password</a></div>           

       <div class="collapse my-3 px-5 formbackground" id="editProfile">
       <div class="row">
       <h3>Profile edition</h3>
        </div>
        <form method="post" name="editProfile" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
           
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for="fullName">Full name <span class="badge badge-warning">Required</span></label>
                        <input type="text" class="form-control" id="fullName" aria-describedby="fullName" placeholder="For example: Doe, John" name="fullName" value="<?php echo $row['name']; ?>" required>
                        <small id="emailHelp" class="form-text text-muted">Enter Last Name then First name.</small>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="email">Email address <span class="badge badge-warning">Required</span></label>
                        <input type="email" class="form-control" id="email" aria-describedby="email" placeholder="Enter email" name="email" value="<?php echo $row['email']; ?>" required>
                        <small id="emailHelp" class="form-text text-muted">Email will never be shared with anyone else.</small>
                    </div>
                </div>                
            </div>

            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" aria-describedby="phoneNumber" placeholder="Enter phone number" value="<?php echo $row['phone']; ?>">
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="text" class="form-control" id="mobile" aria-describedby="mobileNumber" placeholder="Enter mobile phone number" name="mobile" value="<?php echo $row['mobile']; ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md">
                    <div class="form-group">
                        <label for="emailShared">Shared Email</label>
                        <input type="email" class="form-control" id="emailShared" aria-describedby="emailShared" placeholder="Enter shared email" name="emailShared" value="<?php if(isset($row['emailShared'])) { echo $row['emailShared'];} ?>">
                        <small id="emailHelp" class="form-text text-muted">Enter shared email if available.</small>
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
                        <label for="location">Location</label>
                        <input type="text" class="form-control" id="location" name="location" aria-describedby="location" placeholder="For example Doe, John" value="<?php echo $row['location']; ?>">
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-group">
                        <label for="badge">Badge</label>
                        <input type="number" class="form-control" id="badge" aria-describedby="badgeNumber" name="badge" placeholder="Enter badge number" value="<?php echo $row['badge']; ?>">
                        <input type="hidden" name="addingNewUser">
                    </div>
                </div>
                
            </div>                                    

            <button type="submit" id="update" name="export" class="btn btn-success mt-3">Save changes!</button>
        </form>
        
        
       </div>                      
       

  
   <!-- Changing password -->

       <div class="collapse my-3 px-5 formbackground" id="changePassword">
       <h3 class="text-center my-2">Password changing</h3>
 
        <form method="post" name="changePassword" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <input type="hidden" name="paswordChangeTrigger">
            
          <div class="form-group row">
            <label for="currentPassword" class="col-sm col-form-label">Current Password</label>
            <div class="col-sm">
              <input type="password" class="form-control justify-content-center" id="currentPassword" name="passwordCurrent">
            </div>
          </div>
          
          <div class="form-group row">
            <label for="newPassword" class="col-sm col-form-label">New Password</label>
            <div class="col-sm">
              <input type="password" class="form-control" id="newPassword" name="passwordNew">
            </div>
          </div>
          
          <div class="form-group row">
            <label for="confirmPassword" class="col-sm col-form-label">Confirm Password</label>
            <div class="col-sm">
              <input type="password" class="form-control" id="confirmPassword" name="passwordConfirm">
            </div>
          </div>                    
                              
            <button type="submit" id="change" name="export" class="btn btn-success mt-3">Change!</button>
        </form>
        
       </div>   
       </div>
   
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
     
<!-- Checking is brawser IE and conditional applying datapicker -->  
  <script>   
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
     $( function() {
        $( "#dateStarted" ).datepicker();
      } );  
      $( function() {
        $( "#dateFinished" ).datepicker();
      } );
      $( function() {
        $( "#dateStartedVerbal" ).datepicker();
      } );  
}
$(document).ready(function(){      
    // Offset for navbar
      var navHeight = $("#navbarId").height() + 18;
      $("#afterNav").css("marginTop", navHeight);
    
    //Setting body padding-bottom equals to footer height
        var footerHeight = $("#footerwrap").outerHeight();
        $("body").css("padding-bottom", footerHeight);      
      
    //setting autofocus on click
      $("#changeButton").click(function() {
          $("html, body").animate({ scrollTop: $("#afterNav").height() }, 1500);
        });
      $("#editButton").click(function() {
          $("html, body").animate({ scrollTop: $("#afterNav").height() }, 1500);
        });   
});
</script>
<?php include 'footer.php'; ?>
</body>

</html>