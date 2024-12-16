<?php
session_start();
include('connection.php');

if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$user_id = $_SESSION['student_id'];

$query = "SELECT * FROM students WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<h1>Student Dashboard</h1>
<p>Welcome, <?= $student['first_name'] ?> <?= $student['last_name'] ?></p>
<p>Email: <?= $student['email'] ?></p>
<p>Phone: <?= $student['phone'] ?></p>
<p>Address: <?= $student['address'] ?></p>

<a href="logout.php">Logout</a>
