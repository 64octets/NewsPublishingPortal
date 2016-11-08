<?php
$dbUser = "root";
$dbPass = "";
$dbHost = "localhost";
$dbDatabase = "news";

$dbConn = mysqli_connect($dbHost,$dbUser,$dbPass,$dbDatabase);

if(!$dbConn) {
    die ("Database not connected");
}
