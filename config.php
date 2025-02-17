<?php

$server = "localhost";
$username = "root";
$password = NULL;
$database = "details";

$conn = mysqli_connect($server, $username, $password, $database);

if(!$conn){
    echo "Connection Failed : " . mysqli_error .  "" ;
}


?>