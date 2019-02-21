<?php
header('Content-type: text/html; charset=utf-8'); 
ini_set('display_errors',1);
ini_set('error_reporting',1);
ini_set("log_errors", "on");

$USERS["admin"] = "admin"; 
$USERS["nochange"] = "admin";


 
function check_logged(){ 
     global $_SESSION, $USERS; 
     if (!array_key_exists($_SESSION["logged"],$USERS)) { 
          header("Location: index.php"); 
     }; 
}; 
?>