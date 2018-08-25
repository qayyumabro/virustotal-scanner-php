<?php  
include('config.php');
session_destroy();
header("Location: $baseUrl/login.php");
exit();
?>