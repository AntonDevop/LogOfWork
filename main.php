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

if($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnection.php';
    
    if(isset($_POST["newRequester"])){
        $requesterName = $_POST["requesterName"];
        $requesterDepartment = $_POST["requesterDepartment"];
        
        $sql = "INSERT INTO `clients3` (id, Name, Department)
        VALUES (DEFAULT, '".$requesterName."', '".$requesterDepartment."')";
        
        if($database->query($sql) === TRUE) {
            echo "<script>alert('New requester added! Now him/her name will be displayed in autosuggest');</script>";
        } else {
            echo "<script>alert('Error! Something went wrong, try again later');</script>";
        }
    }
    
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
    <link rel="stylesheet" href="js/jquery-ui-1.12.1/jquery-ui.min.css">

    <title>Main page | LogOfWork</title>
    
    <link rel="stylesheet" href="css/main.css">
       
    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Amaranth:700i" rel="stylesheet">
    
    
</head>

<body>
   <!-- Modal for adding new client ↓ -->
<div class="modal fade" id="addRequesterModal" tabindex="-1" role="dialog" aria-labelledby="addRequesterModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRequesterModalLabel">Adding a new Requester</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <p>Enter info of a new requester below.</p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
             <input type="hidden" name="newRequester">
              <div class="form-group">
                <label for="requesterName">Full name</label>
                <input type="text" class="form-control" id="requesterName" placeholder="Enter full name, eg. Doe John" name="requesterName">
              </div>
              
              <div class="form-group">
                <label for="requesterDepartment">Department</label>
                <input type="text" class="form-control" id="requesterDepartment" placeholder="Enter Department" name="requesterDepartment" list="suggests">
                <datalist id="suggests">
                    <option value="Administration">
                    <option value="CEPS">
                    <option value="Commissioning and Start-up">
                    <option value="Construction">
                    <option value="Contracts">
                    <option value="Controls&Power">
                    <option value="Cost Control">
                    <option value="DCC">
                    <option value="Finance">
                    <option value="HES">
                    <option value="HR">
                    <option value="IM">
                    <option value="Industrial Relations">
                    <option value="IT">
                    <option value="Kazakh Content">
                    <option value="Mobilization & Training team">
                    <option value="Operations">
                    <option value="Operations Support">
                    <option value="PDDM">
                    <option value="PGPA">
                    <option value="Planning">
                    <option value="Project Controls">
                    <option value="QA/QC">
                    <option value="Regulatory Affairs">
                    <option value="Security">
                    <option value="Supply Chain Management">
                    <option value="System Completion">
                    <option value="Tengiz Tie-in">
                    <option value="Translation">
                    <option value="Travel RTG">
                    <option value="Turnover Group">
                </datalist>
                
              </div>               
                
              <button type="submit" class="btn btn-primary">Create!</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </form>          
      </div>

    </div>
  </div>
</div>
   <!-- Modal for adding new client ↑ -->
   
   
    <div class="conrainer-fluid px-5">
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top mb-5 justify-content-between" id="navbarId">
        <img id="logo" src="img/logoNav.png" alt="logo picture">
        <h2 class="yellowstrocked">Work Log</h2>
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
                <a class="nav-item nav-link  dropdown-toggle btn btn-warning mx-1 px-2" href="main.php" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="main.php">Main page</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="main.php">Main</a>
                 <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#writtenTable">Written section</a>
                  <a class="dropdown-item" href="#verbalTable">Verbal section</a>                 
                </div>   
                </div>
                
                <a class="nav-item nav-link btn btn-info mx-1 px-2" href="profile.php">My profile</a>                
                
                <a class="nav-item nav-link btn btn-danger mx-1 px-2" href="logout.php">Log out</a>
                
            </div>
            </div>
        </div>
    </nav>            

       <div class="row" id="afterNav">
            <table class="table text-center table-sm">
              <thead class="thead-light">
                <tr>
                  <th scope="col">Your name</th>
                  <th scope="col">Today is</th>
                  <th scope="col">Date</th>                  
                </tr>
              </thead>
              <tbody class="border border-top-0 border-dark">
                <tr>
                  <th scope="row"><?php echo $translatorName; ?></th>
                  <td><?php echo $todayDay; ?></td>
                  <td><?php echo date('F j, Y', strtotime($todayDate)); ?></td>                  
                </tr>
              </tbody>
            </table>
            
       </div>

        <form id="recordForm" method="post" action="inserttodb.php">
<!-- Requester's name -->
            <div class="form-group col-sm-8 offset-sm-2">
                <label for="customerName">Who asked for translation:</label>
                <input type="text" name="requester" class="form-control" id="customerName" aria-describedby="requester" placeholder="Enter first or last name" required autocomplete="off">
                
                <div id="autocompleteBox">
                    
                </div>
                
            </div>
<!-- Requester's name -->

           
<!-- Written or verbal -->
            <div class="row justify-content-center">
               <!--div class="col-sm"-->
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="typeOfTranslation" id="inlineRadio1" value="written">
                    <label class="form-check-label typecheck" for="inlineRadio1">Written</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="typeOfTranslation" id="inlineRadio2" value="verbal">
                    <label class="form-check-label typecheck" for="inlineRadio2">Verbal</label>
                </div>            
               <!--/div-->
            </div>
<!-- Written or verbal -->
           
<!-- Written section -->
            <div id="writtenSection" class="section written">
                <h3 class="text-center">You done <em>WRITTEN</em> translation</h3>   
               
                <div class="form-row">
                  <div class="col-sm-5 offset-sm-2">
                    <label for="docTitle">Document title</label>
                    <input type="text" class="form-control written" id="docTitle" placeholder="Enter a tittle of the translated document here" name="docTitle" >
                  </div>
                  <div class="col-sm-3">
                    <label for="symbols">Symbols</label>
                    <input type="number" class="form-control written" id="symbols" name="symbols" placeholder="Enter number of characters" >
                  </div>
                </div>                                          
                       
                <div class="form-row">
                  <div class="col-sm-3 offset-sm-3">
                    <label for="dateStarted">Date started</label>
                    <input type="date" class="form-control written" id="dateStarted" name="dateStarted" min="2018-01-01" max="2030-01-01" value="<?php echo date('d M Y'); ?>">
                  </div>
                  <div class="col-sm-3">
                    <label for="dateFinished">Date finished</label>
                    <input type="date" class="form-control written" id="dateFinished" name="dateFinished" min="2018-01-01" max="2030-01-01" value="<?php echo date('d M Y'); ?>">
                  </div>
                </div>
                
                <div class="form-row justify-content-center mx-5">
                    <div class="alert alert-danger d-none text-center" role="alert" id="writtenWrongDate">
                      
                    </div>
                </div>
            </div>
            
<!-- Written section -->
           
<!-- Verbal section -->
            <div id="verbalSection" class="section verbal off">
                <h3 class="text-center">You done <em>VERBAL</em> translation</h3>   
                <div class="form-row">
                   <div class="col-sm-8 offset-sm-2">
                        <label for="eventName">Event description</label>
                        <input type="text" class="form-control verbal" id="eventName" placeholder="For example: Weekly Progress Meeting etc." name="eventName">
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-sm-3 offset-sm-2">
                        <label for="date">Date</label>
                        <input type="date" class="form-control verbal" id="dateStartedVerbal" name="date" value="<?php echo date('d M Y'); ?>">
                    </div>

                    <div class="col-sm-3">
                        <label for="time">Time started<span class="small text-muted"> (eg. 08:30, 17:00...)</span></label>
                        <input type="time" class="form-control verbal" id="time" name="time" data-toggle="tooltip" data-placement="top" title="You can enter time just like 0830, 1700 without :">
                    </div>
                    <div class="col-sm-2">
                        <label for="duration">Duration</label>
                        <input type="number" class="form-control verbal" id="duration" name="duration" placeholder="Enter duration in minutes" >    
                    </div>
                </div>
                
                <div class="form-row justify-content-center mx-5">
                    <div class="alert alert-danger d-none text-center" role="alert" id="verbalWrongDate">
                      
                    </div>
                </div>                
                
            </div>
<!-- Verbal section -->
            <div class="row justify-content-center">
               
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
               
            </div>
        </form>
        
        <div id="writtenWeek" class="col-md-8 offset-md-2 col-sm-12">
<?php
        
include 'dbconnection.php';

//getting records from WRITTEN translations table
$sql = "SELECT * FROM writtenDB WHERE dateFinished BETWEEN '".$weekStart."' AND '".$weekEndShow."' AND doneBy ='".$translatorName."' ORDER BY dateFinished DESC";

//(MySQLi Object-oriented)
$result = $database->query($sql);

if ($result->num_rows > 0) {
    echo '<h2 id="writtenTable">Your written translations this week</h2>
    <table class="table table-striped table-light table-hover table-responsive-md">
        <thead>
            <tr>
              <th scope="col">From</th>
              <th scope="col">Document</th>
              <th scope="col">Symbols</th>
              <th scope="col">Started</th>
              <th scope="col">Finished</th>
            </tr>
        </thead>
        <tbody>';
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr class='writtenRow'  data-toggle='tooltip' data-placement='top' title='Select this line by clicking on it if you want to delete this record'>
                <td class='fromtd'>".$row["requesterName"]."</td>
                <td class='doctd'>".$row["docTitle"]."</td>
                <td class='symbolstd'>".number_format($row["symbols"], 0, ',', ' ')."</td>
                <td class='startedtd'>".date('F j, Y', strtotime($row["dateStarted"]))."</td>
                <td class='finishedtd'>".date('F j, Y', strtotime($row["dateFinished"]))."</td>
              </tr>";
    }
    echo "</tbody></table></div>";
} else {
    echo "<h2 class='text-center'>So far you do not have written translations this week</h2> </div>";
}
            
            
//getting records from VERBAL translations table
$sql2 = "SELECT * FROM verbalDB WHERE date BETWEEN '".$weekStart."' AND '".$weekEndShow."' AND doneBy ='".$translatorName."' ORDER BY date DESC";

//(MySQLi Object-oriented)
$result2 = $database->query($sql2);

if ($result2->num_rows > 0) {
    echo '<div class="col-md-8 offset-md-2 col-sm-12"><h2 id="verbalTable">Your verbal translations this week</h2>
    <table class="table table-striped table-light table-hover table-responsive-md">
        <thead>
            <tr>
              <th scope="col">From</th>
              <th scope="col">Event</th>
              <th scope="col">Date</th>
              <th scope="col">Time started</th>
              <th scope="col">Duration</th>
            </tr>
        </thead>
        <tbody>';
    
    // output data of each row
    while($row = $result2->fetch_assoc()) {
        echo "<tr class='verbalRow'  data-toggle='tooltip' data-placement='top' title='Select this line by clicking on it if you want to delete this record'>
                <td class='fromtd'>".$row["requesterName"]."</td>
                <td class='eventtd'>".$row["event"]."</td>
                <td class='datetd'>".date('F j, Y', strtotime($row["date"]))."</td>
                <td class='timetd'>".date('H:i', strtotime($row["timeStarted"]))."</td>
                <td class='durationtd'>".$row["duration"]."</td>
              </tr>";
    }
    echo "</tbody></table></div>";
} else {
    echo "<hr><h2 class='text-center'>So far you do not have verbal translations this week</h2> </div>";
}
?>
    </div>
        <div id="ajaxShow"></div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jquery-3.3.1.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> 
 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
    
    <script src="js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="js/moment.js"></script>
    <script>
	$(".section").hide();
//starting jquery code for textbox
    $(document).ready(function(){
//action on inputing text into customerName textbox
        $("#customerName").keyup(function(){
//grabbing value from textbox
            var query = $(this).val();
//check qyery is not empty and has more than 2 characters
            if(query != '' && query.length > 2) {
//calling for ajax function                
                $.ajax({
//specify url of the file where we send requested data i.e. query variable
                    url:"search.php",
//method of sending information
                    method:"POST",
//define query data
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
             $("#customerName").val($(this).text());
//hiding suggestion div
             $("#autocompleteBox").fadeOut();
        });
        
//hide both section from start
        $(".section").hide();
        
//Selecting which type of translation record should be shown below        
//selecting action on radio click
        $('input[type="radio"]').click(function() {
//assigning to input var value of clicked radio attribute 
            var input = $("." + $(this).attr("value"));  
//hiding all elements with class .section which dows not have input value in it
            $(".section").not(input).hide();
//for toggling make elements with class specified in input variable to be shown on click
            input.show();
//to show written section by default assign .off class with display:none to verbal section which will be overridden by toggling class from var input 
            
//assigning required attribute for all inputs in selected div (written or verbal)
//            var required = $("." + $(this).attr("value") + " :input")
//            required.attr('required', true);
            
//assign required attribute to all related inputs based on choosen type of translation
            if($("#inlineRadio1").is(":checked")) {
                
                $(".verbal").attr('required', false);
                $(".written").attr('required', true);
                
            } else {
                
                $(".written").attr('required', false);
                $(".verbal").attr('required', true);                
            }            
        });
                
    });
    </script>
    


<!-- Checking is brawser IE and conditional applying datapicker -->  
  <script>   
      $(function () {
  $('[data-toggle="tooltip"]').tooltip();
});
      
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
      $( function() {
        $( "#dateStartedVerbal" ).datepicker();
      } );  
} else {
    //applying default value(today) for date inputs of non IE browsers
    document.getElementById('dateStarted').valueAsDate = new Date();
    document.getElementById('dateFinished').valueAsDate = new Date();
    document.getElementById('dateStartedVerbal').valueAsDate = new Date();
}
      
$(document).ready(function(){
    // Offset for navbar
      var navHeight = $("#navbarId").height() + 18;
      $("#afterNav").css("marginTop", navHeight);    
    //Setting body padding-bottom equals to footer height
        var footerHeight = $("#footerwrap").outerHeight();
        $("body").css("padding-bottom", footerHeight);    
    
    
    
    // Limitation on active week ↓ ***********************
    

$( "#dateFinished" ).change(function() {

  //*************************************************************/
//get today's Day of the week for switch    
  var todayDay = moment().format('dddd');
  
//moment.js script for processing dates
  var activeWeekStart;
  var activeWeekEnd;
    
  switch (todayDay) {
    case "Monday":
      activeWeekStart = moment().subtract(2, 'days');
      activeWeekEnd = moment().add(4, 'days');
        break;
    case "Tuesday":
      activeWeekStart = moment().subtract(3, 'days');
      activeWeekEnd = moment().add(3, 'days');
        break;
    case "Wednesday":
      activeWeekStart = moment().subtract(4, 'days');
      activeWeekEnd = moment().add(2, 'days');
        break;
    case "Thursday":
      activeWeekStart = moment().subtract(5, 'days');
      activeWeekEnd = moment().add(1, 'days');
        break;
    case "Friday":
      activeWeekStart = moment().subtract(6, 'days');
      activeWeekEnd = moment();
        break;
    case "Saturday":
      activeWeekStart = moment().subtract(7, 'days');
      activeWeekEnd = moment().add(6, 'days');
        break;
    case "Sunday":
        day = "Sunday";
      activeWeekStart = moment().subtract(1, 'days');
      activeWeekEnd = moment().add(5, 'days');
}
  /**************************************************************/
    // get entered Date
    var finishDateWritten = $("#dateFinished").val(); 
  
var activeWeekStartForIF = activeWeekStart.subtract(1, 'days');
var activeWeekEndForIF = activeWeekEnd.add(1, 'days');
    
 
    //returns true if entered Date beyond acceptable limit and shows error alert section
    if(moment(finishDateWritten).isBetween(activeWeekStartForIF, activeWeekEndForIF)==false){
        
        $("#writtenWrongDate").removeClass("d-none");
        $("#writtenWrongDate").html("You chose a date beyound an active week. <br>The active week started on: " + activeWeekStart.add(1, 'days').format('MMMM Do YYYY') + "<br> and will be finished on: " + activeWeekEnd.subtract(1, 'days').format('MMMM Do YYYY') + " <br>Try again and choose valid date within the active week.");
        $("#dateFinished").val("");
      } else {
          if ( $("#writtenWrongDate").hasClass("d-none") == false) {
            $("#writtenWrongDate").addClass("d-none");
          }
      }
});

    
$( "#dateStartedVerbal" ).change(function() {
    
  //*************************************************************/
//get today's Day of the week for switch    
  var todayDay = moment().format('dddd');
  
//moment.js script for processing dates
  var activeWeekStart;
  var activeWeekEnd;
    
  switch (todayDay) {
    case "Monday":
      activeWeekStart = moment().subtract(2, 'days');
      activeWeekEnd = moment().add(4, 'days');
        break;
    case "Tuesday":
      activeWeekStart = moment().subtract(3, 'days');
      activeWeekEnd = moment().add(3, 'days');
        break;
    case "Wednesday":
      activeWeekStart = moment().subtract(4, 'days');
      activeWeekEnd = moment().add(2, 'days');
        break;
    case "Thursday":
      activeWeekStart = moment().subtract(5, 'days');
      activeWeekEnd = moment().add(1, 'days');
        break;
    case "Friday":
      activeWeekStart = moment().subtract(6, 'days');
      activeWeekEnd = moment();
        break;
    case "Saturday":
      activeWeekStart = moment().subtract(7, 'days');
      activeWeekEnd = moment().add(6, 'days');
        break;
    case "Sunday":
        day = "Sunday";
      activeWeekStart = moment().subtract(1, 'days');
      activeWeekEnd = moment().add(5, 'days');
}  
  /**************************************************************/    
    
    // get entered Date
    var finishDateVerbal = $("#dateStartedVerbal").val(); 
    
var activeWeekStartForIF = activeWeekStart.subtract(1, 'days');
var activeWeekEndForIF = activeWeekEnd.add(1, 'days');    
    
    if(moment(finishDateVerbal).isSame(activeWeekStart) == false){
    //returns true if entered Date beyond acceptable limit and shows error alert section
    if(moment(finishDateVerbal).isBefore(activeWeekStart) || moment(finishDateVerbal).isAfter(activeWeekEnd)){
        
        $("#verbalWrongDate").removeClass("d-none");
        $("#verbalWrongDate").html("You chose a date beyound an active week. <br>The active week started on: " + activeWeekStart.add(1, 'days').format('MMMM Do YYYY') + "<br> and will be finished on: " + activeWeekEnd.subtract(1, 'days').format('MMMM Do YYYY') + " <br>Try again and choose valid date within the active week.");
        $("#dateStartedVerbal").val("");
      } else {
          if ( $("#verbalWrongDate").hasClass("d-none") == false) {
            $("#verbalWrongDate").addClass("d-none");
          }
      }
    }
});
    // Limitation on active week ↑ ***********************    
    
        
    
    
    
    
    
    
    
    
    
    // Edit a record from the rendered table ↓ ***********************    
    
    //hower processing ↓
    $.merge($(".writtenRow"), $(".verbalRow")).hover(function() {
        $(this).css("cursor", "pointer");
        $(this).tooltip();
    });
    //hower processing ↑

    //joint selectors for both written and verbal tables processing click ↓
    $.merge($(".writtenRow"), $(".verbalRow")).click(function() {

        //adding "Delete" button
        if ($(this).next(".btn").length == 0){
            //$(this).append('<button type="button" class="btn btn-danger deleteRecord">Delete</button>');
            $(this).after('<button type="button" class="btn btn-danger deleteRecord">Delete</button>');
        }

        
        //define which table's row clicked  
        if ($(this).hasClass("writtenRow")) {


            var writtenFrom = $(this).find(".fromtd").html();
            var writtenDocument = $(this).find(".doctd").html();
            var writtenSymbols = $(this).find(".symbolstd").html();
            writtenSymbols = Number(writtenSymbols.replace(/\s+/g, ''));
            var writtenStarted = $(this).find(".startedtd").html();
            var writtenFinished = $(this).find(".finishedtd").html();
                        

        } else {
           
            var verbalFrom = $(this).find(".fromtd").html();
            var verbalEvent = $(this).find(".eventtd").html();
            var verbalDate = $(this).find(".datetd").html();
            var verbalTime = $(this).find(".timetd").html();
            var verbalDuration = $(this).find(".durationtd").html();            
            
        }
        
        //delete button click processing
        $(".deleteRecord").click(function() {
            
            //fetching record's data to object
            var by = "<?php echo $translatorName; ?>";
            
            if(writtenFrom){
                var arrayRec = {
                    type: "written",
                    by: by,
                    from: writtenFrom, 
                    doc: writtenDocument, 
                    symbols: writtenSymbols,
                    start: writtenStarted,
                    finish: writtenFinished
                }
            } else {
                var arrayRec = {
                    type: "verbal",
                    by: by,
                    from: verbalFrom, 
                    event: verbalEvent, 
                    date: verbalDate,
                    time: verbalTime,
                    duration: verbalDuration
                  }
              }
            
            $(this).prev().remove();
            $(this).remove();
            
            $.ajax({
                url:"deleterecord.php",
                method:"POST",
                data: {arrayRec:arrayRec},
                success:function(data){
                        alert("You have deleted the record succcessfuly!");
                    //$("#ajaxShow").html(data);
                    }
                
            });
        });

    });
    //joint selectors for both written and verbal tables processing click ↑
    
    // Edit a record from the rendered table ↑ ***********************    
    
    
}); //$(document).ready ends
       
</script>
  <?php include 'footer.php'; ?>
<?php include 'metrics.php'; ?>  
</body>

</html>