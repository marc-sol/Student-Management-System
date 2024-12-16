<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $subject_id = $_GET['id'];

    $query = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $subject = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $subject_name = $_POST['subject_name'];

        $query = "UPDATE subjects SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si', $subject_name, $subject_id);
        $stmt->execute();

        header("Location: admin_manage_subjects.php");
    }
}
?>

<form method="POST">
    Subject Name: <input type="text" name="subject_name" value="<?= $subject['name'] ?>" required><br>
    <input type="submit" value="Update Subject">
</form>
