<?php
//starting session for login feature
session_start();
$errorMessage = "";
if(array_key_exists("email", $_SESSION) AND $_SESSION["email"] AND array_key_exists("secretCode", $_SESSION) AND $_SESSION["secretCode"]) {
    if($_SERVER["REQUEST_METHOD"] == "POST") {
    //code for password changing
    $secretCode = $_POST["secretCodeInput"];
    $secretCode = trim($secretCode);
    if($secretCode == $_SESSION["secretCode"]){
        $newPassword = $_POST["newPasswordInput"];
        $passwordConfirm = $_POST["newPasswordConfirmInput"];
        $securePassword = password_hash($newPassword,PASSWORD_DEFAULT);
        
        if($newPassword == $passwordConfirm){
                include 'dbconnection.php';
                $sql = 'UPDATE usersTable 
                    SET
                        password="'.$securePassword.'"
                    WHERE 
                        email="'.$_SESSION['email'].'" LIMIT 1';
                if ($database->query($sql) === TRUE) {
                    echo "<script> 
                    alert('You successfully changed your password');
                    </script>";
                    $errorMessage = '
                    <div class="alert alert-success" role="alert">
                        <h2 class="text-center">You successfully changed your password, email was sent to you. Click <a href="index.php">here</a> to log in using your new password</h2>
                    </div>';
                    
                    //sending new password to email
                    $to = filter_var($_SESSION["email"], FILTER_VALIDATE_EMAIL);
                    $subject = "Your new Password for Work Log";
                    $msg = "Hello! \n \n You successfully changed your password for Wrok Log system. \n Please remember your new password: ". $newPassword ." \n
            \n
            Please do not reply to this email it was created automatically.\n
            \n
            Sincerely yours,
            Lonely Work Log Robot.
            ";
                    $headers = "From: passwordReseting@worklog.org";
                    mail($to, $subject, $msg, $headers);
                    
                    unset($_SESSION["secretCode"]);
                    unset($_SESSION["email"]);
                    session_destroy();
                                        
                } else {
                    $errorMessage = '
                    <div class="alert alert-danger" role="alert">
                        <h2 class="text-center">Error changing password</h2>
                    </div>';
                }                
            } else {
                    $errorMessage = '
                    <div class="alert alert-danger" role="alert">
                        <h2 class="text-center">New password and Confirm password does not match, they should be the same. Try again!</h2>
                    </div>';
            }
        } else {
        $errorMessage = '
            <div class="alert alert-danger" role="alert">
                <h2 class="text-center">Secret code does not match with that provided to you by email. Try again!</h2>
            </div>';
        }
    }
    
} else {
    //header("Location: main.php");
}

?>

<html lang="en">

<head>
    <title>Password reset</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Amaranth:700i" rel="stylesheet">
    
    <link rel="stylesheet" href="css/main.css">
    
</head>

<body>
<div class="conrainer my-5 py-5">
   <?php echo $errorMessage; ?>
    <div class="row justify-content-center">
        <div class="alert alert-info" role="alert">
            <h2 class="text-center">Soon you will get email with a secret code. To proceed with password resetting please fill in form below</h2>
        </div>
    </div>
    <div class="row justify-content-center">
        <form id="resetForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label for="secretCodeInput">Secret code</label>
                <input type="text" class="form-control" id="secretCodeInput" aria-describedby="emailHelp" placeholder="Secret code" name="secretCodeInput" required>
                <small id="emailHelp" class="form-text text-muted">Enter or paste here secret code which you received by email.</small>
            </div>
            <div class="form-group">
                <label for="newPasswordInput">New Password</label>
                <input type="password" class="form-control" id="newPasswordInput" placeholder="Password" name="newPasswordInput" required>
            </div>
            <div class="form-group">
                <label for="newPasswordConfirmInput">Confirm Password</label>
                <input type="password" class="form-control" id="newPasswordConfirmInput" placeholder="Password" name="newPasswordConfirmInput" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

</div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    
</body>

</html>