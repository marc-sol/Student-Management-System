<?php

include_once("connection.php");
$con = connection();

if(isset($_POST['submit'])){

    $fname = $_POST['firstname'];
    $lname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    
        $sql = "INSERT INTO `student_list`( `first_name`, `last_name`, `birthdate`,`gender`)
        VALUES ('$fname','$lname', '$birthdate','$gender')";

    $con->query($sql) or die ($con->error);

    echo header("Location: index.php");

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management Sytem</title>
    <link rel = "stylesheet" href="style.css">
</head>
<body>

    <form action="" method="post">
        <label>First Name</label>
        <input type ="text" name = "firstname" id = "firstname">

        <label>Last Name</label>
        <input type ="text" name = "lastname" id = "lastname">

        <label>Birthdate</label>
        <input type ="text" name = "birthdate" id = "birthdate">

        <label>Gender</label>
        <select name = "gender" id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>


        <input type="submit" name="submit" value="Submit Form">

    </form>


</body>
</html>