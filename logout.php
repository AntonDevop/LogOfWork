<?php 
session_start();

unset($_SESSION["name"]);
unset($_SESSION["manager"]);
setcookie("name", "", time()-3600);
setcookie("manager", "", time()-3600);
session_destroy();
header("Location: index.php");
session_destroy();

?>