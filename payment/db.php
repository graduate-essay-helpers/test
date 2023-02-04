<?php

$host = "localhost:3306";

$user = "graduate_essay";

$password = "p@ssword2022";

$dbname = "graduate_essay";


$errors = array();


$con = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
