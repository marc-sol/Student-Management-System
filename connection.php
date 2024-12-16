<?php 

function connection(){
    $host = "localhost";           
    $username = "root";            
    $password = "";                
    $dbname = "student_management"; 
    $port = 3307;                  

    $con = new mysqli($host, $username, $password, $dbname, $port);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error); 
    }

    echo "Connected successfully to the database: $dbname!";

    return $con; 
}

$con = connection(); 

$con->close();

?>
