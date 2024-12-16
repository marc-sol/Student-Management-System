<?php 

function connection() {
    $host = "localhost";           
    $username = "root";            
    $password = "";                
    $dbname = "student_management"; 
    $port = 3307;                  

    $conn = new mysqli($host, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); 
    }

    return $conn; 
}

$conn = connection(); 

?>
