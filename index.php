<?php
//starting session for login feature
session_start();

if(array_key_exists("name", $_SESSION) AND $_SESSION["name"] OR (array_key_exists("name", $_COOKIE) AND $_COOKIE["name"])) {
    header("Location: main.php");
}

$messageError = "";
$email = "";
$password = "";    

if($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnection.php';
    
    if(isset($_POST["passwordReset"])){
        
        $resetEmail = $_POST["resetEmail"];
        $query = "SELECT * FROM usersTable WHERE email = '".mysqli_real_escape_string($database, $resetEmail)."'";
        $result = mysqli_query($database, $query);
        $row = mysqli_fetch_array($result);
        
        if (mysqli_num_rows($result) == 0) {
            $messageError = "Sorry, email you entered cannot be used to reset password since it either does not exist or wrong. <br> Try again!";
        } else {
            $secretCode = substr($row["password"], 8, 8);  
            // 8 symbols secrete code for sending to email to reset password;            
            //avoiding malicious code from user input by validating email 
            $to = filter_var($resetEmail, FILTER_VALIDATE_EMAIL);
            $subject = "Password resetting for Work Log";
            $msg = "Hello! \n \n You got this message since you initiated password resetting process for your account on LogOf.Work system. \n Please enter this secret code to change your password: ". $secretCode ." \n
            \n
            Please do not reply to this email it was created automatically.\n
            \n
            If you did not initiate this password resetting process just ignore it. The system is secure enough and nobody except for you know your password and it is only you who can change it. 
            \n
            If you keep getting such emails by mistake write about it to AntonDevop@ya.ru
            \n
            Sincerely yours,
            Lonely Log Of Work Robot.
            ";
            $headers = "From: passwordresetting@worklog.org";
            mail($to, $subject, $msg, $headers);
            
            header( "refresh:3; url=passwordreset.php" );
            $_SESSION['secretCode'] = $secretCode;
            $_SESSION['email'] = $resetEmail;
            
        }

    } else {
        
// reCaptcha code
   $recaptchaResponse = $_POST['g-recaptcha-response']; $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Le1xkYUAAAAAEfSDTUT-xSqVhycr9LhXnlnFP-w&response=".$recaptchaResponse."&remoteip=".$_SERVER['REMOTE_ADDR']);
    $obj = json_decode($response);
    if($obj->success == true)
    {
    $email = $_POST['email'];
    
    if(array_key_exists("manager", $_POST) && $_POST["manager"] == 1) {
        $manager = 1;
    } else {
        $manager = 0;
    }
    
    //CHECKING DOES EMAIL EXIST
    $query = "SELECT * FROM usersTable WHERE email = '".mysqli_real_escape_string($database, $email)."'";
    
    $resultEmail = mysqli_query($database, $query);
    //CONVERTING RESULT OF THE SEARCH IN DATABASE TO ARRAY
    $row = mysqli_fetch_array($resultEmail);
    
    if (mysqli_num_rows($resultEmail) == 0) {
        $messageError = "Sorry, this email does not exist";
    } else {
        $hash = $row["password"];
        $salt = $row["id"];
        $password = $_POST["password"];
        $passwordCheck = password_verify($password,$hash);
        
        
        
        //CHECKING IS PASSWORD RIGHT
        if($passwordCheck){

            //manager login page
            if($manager == 1 && $row['manager'] == "yes") {
                header("Location: manager.php");
        
                //setting Session variables
                $_SESSION['name'] = $row['name'];
                $_SESSION['manager'] = $row['manager'];

                if($_POST['stayLoggedIn'] == 1) {
                    setcookie("name", $row["name"], time()+ 60*60*24*365);
                    setcookie("manager", $row["manager"], time()+ 60*60*24*365);
                }
            }
    
            //REDIRECTING TO MAIN PAGE
            header("Location: main.php");
    
            //setting Session variables
            $_SESSION['name'] = $row['name'];
    
            if($_POST['stayLoggedIn'] == 1) {
                setcookie("name", $row["name"], time()+ 60*60*24*365);
            }    
            
        } else {
            $messageError = "Sorry, password does not match. Try again";    
        }
}
    }
    else
    {
        echo "<script>alert('Error! \n It is either you failed antibot test or your address is too suspicious! Try again a little bit later')</script>";
    }
    
}
}


?>

<html lang="en">

<head>
    <title>Log of work</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Amaranth:700i" rel="stylesheet">
    
    
    <style type="text/css">
        body {
          background-image: url("../img/logInBackground.jpg");
            /* Background image is centered vertically and horizontally at all times */
          background-position: center center;

          /* Background image doesn't tile */
          background-repeat: no-repeat;

          /* Background image is fixed in the viewport so that it doesn't move when 
             the content's height is greater than the image's height */
          background-attachment: fixed;

          /* This is what makes the background image rescale based
             on the container's size */
          background-size: cover;

          /* Set a background color that will be displayed
             while the background image is loading */
          background-color: #464646;
        }
        #welcome {
            font-family: 'Amaranth', sans-serif, Georgia, Serif;
            font-size: 300%;            
            position: relative;
            top: 25px;
            margin-bottom: 35px;            
        }
        .yellowstrocked {
            color: yellow;
               -webkit-text-stroke: 1px black;            
            text-shadow:
                3px 3px 0 #000,
                -1px -1px 0 #000,  
                1px -1px 0 #000,
                -1px 1px 0 #000,
                1px 1px 0 #000;
        }
        .form-check-input {
            width: 20px;
            height: 20px;
        }
        #loginForm {
            width: 50%;
            font-family: 'Amaranth', sans-serif, Georgia, Serif;
            font-size: 150%;            
        }
        #errorMes {
	        color: red;
            font-weight: bold;
        }
        iframe {
          border-radius: 5px;
          border: groove 3px grey;
        }
#footerwrap {
  /*font-size: 50%;*/
  height: auto;
  width: 100%;
  position: relative;
  bottom: 0;
  text-align: center;
  padding-top: 25px;
  margin-top: 25px;
  opacity: 0.8;
  color: black;
}
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
       function onSubmit(token) {
         document.getElementById("loginForm").submit();
       }
     </script>
     
</head>

<body>
   <!-- Modal -->
<div class="modal fade" id="passwordResetModal" tabindex="-1" role="dialog" aria-labelledby="passwordResetModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="passwordResetModalLabel">Password reminding</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p>Enter your email address that you used to log in. Email with your password will be sent to you.</p>
            <form id="passres" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
             <input type="hidden" name="passwordReset">
              <div class="form-group">
                <label for="emailForPasswordRestore">Email address</label>
                <input type="email" class="form-control" id="emailForPasswordRestore" placeholder="Enter email" name="resetEmail">
              </div>              
                
              <button type="submit" class="btn btn-primary">Remind Password!</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>          
      </div>

    </div>
  </div>
</div>
   <!-- Modal -->
   
         
    <div class="container justify-content-center">
        <div class="row justify-content-center">
        <h1 id="welcome" class="yellowstrocked">Sign in to your Log of Work!</h1>
        </div>
        
        <div class="row justify-content-center">
        <?php 
        if ($messageError != '') {
            $output = '
            <div class="alert alert-danger text-center" role="alert">'.$messageError.' 
            <p><button class="btn btn-primary my-3" data-toggle="modal" data-target="#passwordResetModal">Forgot password?</button></p></div>
            ';
            echo $output;
        }
        ?>        
        </div>
        
        <div class="row justify-content-center">
        <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
           
            <div class="form-group">
                <label for="emailId" class="yellowstrocked">Email address</label>
                <input type="email" name="email" class="form-control" id="emailId" aria-describedby="emailHelp" placeholder="Enter email" required>                
            </div>
            
            <div class="form-group">
                <label for="passwordId" class="yellowstrocked">Password</label>
                <input type="password" name="password" class="form-control" id="passwordId" placeholder="Enter your password" required>
            </div>
            
            <div class="form-check-inline d-flex justify-content-center">
                <label class="form-check-label mx-5 yellowstrocked">
                  <input type="checkbox" class="form-check-input" name="stayLoggedIn" value="1">
                  Stay logged in
                </label>
                <label class="form-check-label mx-5 yellowstrocked">
                  <input type="checkbox" class="form-check-input" name="manager" value="1">
                  Manager
                </label>
                
            </div>
            <div class="form-group d-flex justify-content-center">
            <input type="submit" class="g-recaptcha btn btn-primary" value="Submit" data-sitekey="6Le1xkYUAAAAAAGAgtzNn1Q092hF6mXe5zCYvCyy" data-callback="onSubmit">            
            </div>
        </form>
        
        
        </div>
        
        <div class="row justify-content-center">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/6chr7FVFzvw?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>
    <div id="footerwrap">
<div class="conrainer-fluid" id="footer">
   <div class="row justify-content-center">
       <p class="text-muted">Developed by <a href="https://antondevop.com" target="_blank">AntonDevop</a></p>
   </div>  
</div>
</div>
    
<?php include 'metrics.php'; ?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    
    <script type="text/javascript">
        
        function isEmail(email) {
                var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                return regex.test(email);
            }
        
        $("#loginForm").submit(function(e) {
            e.preventDefault();
                
            var errorMessage = "";
            var fieldsMissing = "";
            
            if ($("#emailId").val() == "") {
                    fieldsMissing += "<br>Email";
                }
            if ($("#passwordId").val() == "") {
                    fieldsMissing += "<br>Password";
                }
            if (fieldsMissing != "") {
                errorMessage += "<p>The following field(s) are required: " + fieldsMissing + "</p>";
                }
            //Checking e-mail
                if ($("#emailId").val()) {
                    if (isEmail($("#emailId").val()) == false) {
                    errorMessage += "<p>Your email is not valid</p>";
                    }
                }
                                
                if (errorMessage != "") {
                    $("#errorMes").html(errorMessage); 
                } else {
                    $("#loginForm").unbind('submit').submit();
                }
        });
    </script>

</body>

</html>