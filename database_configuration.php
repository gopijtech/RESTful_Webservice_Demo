<?php

// Mysql database
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "databasename";

// Create connection
$conn = mysql_connect($servername, $username, $password);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysql_select_db($dbname);