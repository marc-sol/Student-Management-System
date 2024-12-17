<?php
session_start();
include('connection.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_name = $_POST['subject_name'];

    $query = "INSERT INTO subjects (name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $subject_name);
    $stmt->execute();

    header("Location: admin_manage_subjects.php");
}

$query = "SELECT * FROM subjects";
$result = $conn->query($query);
?>

<h1>Manage Subjects</h1>
<a href="logout.php">Logout</a>

<form method="POST">
    Subject Name: <input type="text" name="subject_name" required><br>
    <input type="submit" value="Add Subject">
</form>

<h2>Existing Subjects</h2>
<table border="1">
    <tr>
        <th>Subject Name</th>
        <th>Actions</th>
    </tr>
    <?php while ($subject = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $subject['name'] ?></td>
        <td>
            <a href="edit_subject.php?id=<?= $subject['id'] ?>">Edit</a>
            <a href="delete_subject.php?id=<?= $subject['id'] ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
