<?php
session_start();
include('db.php');

if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$student_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = :id");
$stmt->execute(['id' => $student_id]);
$student = $stmt->fetch();

$stmt_grades = $pdo->prepare("SELECT subjects.name AS subject_name, grades.grade 
                              FROM grades
                              JOIN subjects ON grades.subject_id = subjects.subject_id
                              WHERE grades.student_id = :id");
$stmt_grades->execute(['id' => $student_id]);
$grades = $stmt_grades->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
</head>
<body>

<h1>Student Details</h1>
<p>Name: <?= htmlspecialchars($student['first_name']) ?> <?= htmlspecialchars($student['last_name']) ?></p>
<p>Email: <?= htmlspecialchars($student['email']) ?></p>
<p>Phone: <?= htmlspecialchars($student['phone']) ?></p>

<h2>Subjects and Grades</h2>
<table>
    <thead>
        <tr>
            <th>Subject</th>
            <th>Grade</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grades as $grade): ?>
        <tr>
            <td><?= htmlspecialchars($grade['subject_name']) ?></td>
            <td><?= htmlspecialchars($grade['grade']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
