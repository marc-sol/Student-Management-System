<?php
session_start();
include('connection.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $grade = $_POST['grade'];

    $query = "INSERT INTO grades (student_id, subject_id, grade) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $student_id, $subject_id, $grade);
    $stmt->execute();

    header("Location: admin_manage_grades.php");
}

$query_students = "SELECT * FROM students";
$result_students = $conn->query($query_students);

$query_subjects = "SELECT * FROM subjects";
$result_subjects = $conn->query($query_subjects);
?>

<h1>Assign Grades</h1>
<a href="logout.php">Logout</a>

<form method="POST">
    Student:
    <select name="student_id">
        <?php while ($student = $result_students->fetch_assoc()): ?>
        <option value="<?= $student['id'] ?>"><?= $student['first_name'] . ' ' . $student['last_name'] ?></option>
        <?php endwhile; ?>
    </select><br>

    Subject:
    <select name="subject_id">
        <?php while ($subject = $result_subjects->fetch_assoc()): ?>
        <option value="<?= $subject['id'] ?>"><?= $subject['name'] ?></option>
        <?php endwhile; ?>
    </select><br>

    Grade: <input type="text" name="grade" required><br>
    <input type="submit" value="Assign Grade">
</form>
