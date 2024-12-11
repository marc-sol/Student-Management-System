<?php
session_start();
include('db.php');

if ($_SESSION['role'] !== 'student') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM students WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$student = $stmt->fetch();
?>

<h1>Welcome, <?= $student['first_name'] ?> <?= $student['last_name'] ?></h1>

<p>Email: <?= $student['email'] ?></p>
<p>Phone: <?= $student['phone'] ?></p>
<p>Address: <?= $student['address'] ?></p>

<a href="edit_profile.php">Edit Profile</a>
