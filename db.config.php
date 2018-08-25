<?php
//Table prefix
$tbl_prefex = "";

//Database information
$dbhost = "";
$dbname = "";
$dbuser = "";
$dbpass = "";

//Connection to database
try{
$handler = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
die("unable to connect to database " . $e->getMessage());
}
