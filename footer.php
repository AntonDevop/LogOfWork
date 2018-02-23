<?php

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["help"]) || isset($_POST["feedback"])) {
    include 'dbconnection.php';
    
    //getting records from written
    $query = "SELECT * FROM usersTable WHERE name ='".$translatorName."'";

    $result = mysqli_query($database, $query);
    $row = mysqli_fetch_array($result);
    $by = $row['email'];
    
    $to = "antondevop@ya.ru";
    $headers = "From: feedback@LogOf.Work"."\r\n"."CC: ".$by."";

    
    if(isset($_POST["help"])){
        
        $subject = "Question from ".$translatorName;
        
        if(isset($_POST["contactWay"])&& $_POST["contactWay"] != ""){
            $msg = $_POST["questionTextarea"]."\r\n Alternative way of contacting: ".$_POST["contactWay"];
        } else {
        $msg = $_POST["questionTextarea"];            
        }
        
        mail($to, $subject, $msg, $headers);
    } else {
        
        $subject = "Feedback from ".$translatorName;
        $msg = $_POST["feedbackTextarea"];
        if(isset($_POST["contactWay"])&& $_POST["contactWay"] != ""){
            $msg = $_POST["feedbackTextarea"]."\r\n Alternative way of contacting: ".$_POST["contactWay"];
        } else {
        $msg = $_POST["feedbackTextarea"];            
        }        
        mail($to, $subject, $msg, $headers);
    }
    
}
?>

<!-- Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalLabel">Ask your question</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p>Enter your question below and provide most convenient way of contacting you.</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
             <input type="hidden" name="help">
              <div class="form-group">
                <label for="question">Enter your question</label>
                  <textarea type="text" class="form-control" id="questionTextarea" placeholder="Start typing question here..." name="questionTextarea" rows="4" cols="50" required></textarea>
              </div>
                <p>By default you will get answer to your email used for log in, if you want another way of contacting you, please specify it below</p>
              <div class="form-group">
                <label for="contactWay">Contact me using</label>
                <input type="text" class="form-control" id="contactWay" placeholder="You can type here alternative email, mobile number etc." name="contactWay">
              </div>
                
              <button type="submit" class="btn btn-primary">Create!</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>          
      </div>

    </div>
  </div>
</div>
   <!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="feedbackModalLabel">Feedback and suggestions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p>Please provide your feedback or suggestions if any below.</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
             <input type="hidden" name="feedback">
              <div class="form-group">
                <label for="feedbackTextarea">Your comments</label>
                  <textarea type="text" class="form-control" id="feedbackTextarea" placeholder="Start typing here..." name="feedbackTextarea" rows="4" cols="50" required></textarea>
              </div>
              
                <p>By default you will get answer to your email used for log in, if you want another way of contacting you, please specify it below</p>
              <div class="form-group">
                <label for="contactWay2">Contact me using</label>
                <input type="text" class="form-control" id="contactWay2" placeholder="You can type here alternative email, mobile number etc." name="contactWay">
              </div>            
                
              <button type="submit" class="btn btn-primary">Create!</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>          
      </div>

    </div>
  </div>
</div>
   <!-- Modal -->   

<div id="footerwrap">
<div class="conrainer-fluid" id="footer">
   <div class="row">
       <div class="col-sm">
           <h3>Guide</h3>
             <li><a href="#" onClick="MyWindow=window.open('https://www.youtube.com/embed/hFgEVKygBNs?rel=0','MyWindow',width=854,height=480); return false;">Intro</a></li>
             <li><a href="#" onClick="MyWindow=window.open('https://www.youtube.com/embed/e3e1u1G5s70?rel=0','MyWindow',width=854,height=480); return false;">Making records</a></li>
             <li><a href="#" onClick="MyWindow=window.open('https://www.youtube.com/embed/KgillWUot9c?rel=0','MyWindow',width=854,height=480); return false;">Adding a client</a></li>
             <li><a href="#" onClick="MyWindow=window.open('https://www.youtube.com/embed/06dZhf2_x1Q?rel=0','MyWindow',width=854,height=480); return false;">Profile editing</a></li>
             <li><a href="#" onClick="MyWindow=window.open('https://www.youtube.com/embed/acSlV4kdZf8?rel=0','MyWindow',width=854,height=480); return false;">Changing password</a></li>
       </div>
       <div class="col-sm">
           <?php 
            if(isset($_SESSION['manager'])){
                echo ' 
                  <h3>Guide for Managers</h3>
                  <li><a href="#" onClick="MyWindow=window.open(\'https://www.youtube.com/embed/x5xYaMaWd98?rel=0\',\'MyWindow\',width=854,height=480); return false;">Manager intro</a></li>
                  <li><a href="#" onClick="MyWindow=window.open(\'https://www.youtube.com/embed/l3ac5DOO1eo?rel=0\',\'MyWindow\',width=854,height=480); return false;">Report creation</a></li>
                  <li><a href="#" onClick="MyWindow=window.open(\'https://www.youtube.com/embed/-HdCqRp1OWI?rel=0\',\'MyWindow\',width=854,height=480); return false;">Adding a user</a></li>
                  <li><a href="#" onClick="MyWindow=window.open(\'https://www.youtube.com/embed/ouiHzlIAEw0?rel=0\',\'MyWindow\',width=854,height=480); return false;">Deleting a translator</a></li>
                ';                
            } else {
                echo '<img src="img/logoFooter.png" class="img-fluid" id="logoFooter" alt="logo picture">';
            }
           ?>
       </div>
       <div class="col-sm">
           <h3>Contacts</h3>
           <p>Have troubles? <br> Click <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#helpModal">here</button> to ask your question!</p>
         
         <p>If you <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#feedbackModal">send</button> any feedback or suggestions <br> it will be highly appreciated.</p>
       </div>
   </div>
  
   <div class="row justify-content-center">
       <small class="text-muted">Developed by AntonDevop</small>
   </div>  
</div>
</div>