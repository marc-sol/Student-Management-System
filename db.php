<?php
$host = 'localhost';
$dbname = 'student_management';
$username = 'root'; 
$password = '';
$port = 3307;

$con = new mysqli($host, $username, $password, $dbname, $port);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error); 
}

echo "Connected successfully!";
?>
