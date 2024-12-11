<?php
session_start();
include('db.php');

if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->query("SELECT * FROM students");
$students = $stmt->fetchAll();
?>

<h1>Admin Dashboard</h1>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?= $student['first_name'] ?> <?= $student['last_name'] ?></td>
            <td><?= $student['email'] ?></td>
            <td><?= $student['phone'] ?></td>
            <td><a href="edit_student.php?id=<?= $student['student_id'] ?>">Edit</a> | 
                <a href="delete_student.php?id=<?= $student['student_id'] ?>">Delete</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
