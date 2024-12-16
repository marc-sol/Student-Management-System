<?php
session_start();
include('connection.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$query_students = "SELECT * FROM students";
$students_result = $conn->query($query_students);

$query_subjects = "SELECT * FROM subjects";
$subjects_result = $conn->query($query_subjects);

?>

<h1>Admin Dashboard</h1>
<a href="logout.php">Logout</a>

<h2>Manage Students</h2>
<a href="add.php">Add New Student</a>

<h2>Manage Grades</h2>
<form method="POST" action="assign_grade.php">
    Student:
    <select name="student_id">
        <?php while ($student = $students_result->fetch_assoc()): ?>
        <option value="<?= $student['id'] ?>"><?= $student['first_name'] . ' ' . $student['last_name'] ?></option>
        <?php endwhile; ?>
    </select><br>

    Subject:
    <select name="subject_id">
        <?php while ($subject = $subjects_result->fetch_assoc()): ?>
        <option value="<?= $subject['id'] ?>"><?= $subject['name'] ?></option>
        <?php endwhile; ?>
    </select><br>

    Grade: <input type="text" name="grade" required><br>
    <input type="submit" value="Assign Grade">
</form>

<h2>Attendance</h2>
<form method="POST" action="mark_attendance.php">
    Student:
    <select name="student_id">
        <?php while ($student = $students_result->fetch_assoc()): ?>
        <option value="<?= $student['id'] ?>"><?= $student['first_name'] . ' ' . $student['last_name'] ?></option>
        <?php endwhile; ?>
    </select><br>

    Subject:
    <select name="subject_id">
        <?php while ($subject = $subjects_result->fetch_assoc()): ?>
        <option value="<?= $subject['id'] ?>"><?= $subject['name'] ?></option>
        <?php endwhile; ?>
    </select><br>

    Date: <input type="date" name="date" required><br>
    Status: 
    <select name="status">
        <option value="present">Present</option>
        <option value="absent">Absent</option>
        <option value="late">Late</option>
    </select><br>
    <input type="submit" value="Mark Attendance">
</form>

<h2>Generate Reports</h2>
<a href="generate_reports.php">Generate Attendance Report</a>
