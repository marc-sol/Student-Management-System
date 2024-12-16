<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    $query = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $birthdate = $_POST['birthdate'];
        $gender = $_POST['gender'];

        $query = "UPDATE students SET first_name = ?, last_name = ?, birthdate = ?, gender = ?, WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssi', $first_name, $last_name, $email, $phone, $address, $student_id);
        $stmt->execute();

        header("Location: admin_dashboard.php");
    }
}
?>

<form method="POST">
    First Name: <input type="text" name="first_name" value="<?= $student['first_name'] ?>" required><br>
    Last Name: <input type="text" name="last_name" value="<?= $student['last_name'] ?>" required><br>
    Email: <input type="email" name="email" value="<?= $student['email'] ?>"><br>
    Phone: <input type="text" name="phone" value="<?= $student['phone'] ?>"><br>
    Address: <textarea name="address"><?= $student['address'] ?></textarea><br>
    <input type="submit" value="Update Student">
</form>
