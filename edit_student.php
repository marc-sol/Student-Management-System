<?php
session_start();
include('db.php');

if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$student_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = :student_id");
$stmt->execute(['student_id' => $student_id]);
$student = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $pdo->prepare("UPDATE students SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, address = :address WHERE student_id = :student_id");
    $stmt->execute([
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'student_id' => $student_id
    ]);

    header('Location: admin_dashboard.php');
    exit();
}
?>

<form method="POST">
    <input type="text" name="first_name" value="<?= $student['first_name'] ?>" required>
    <input type="text" name="last_name" value="<?= $student['last_name'] ?>" required>
    <input type="email" name="email" value="<?= $student['email'] ?>" required>
    <input type="text" name="phone" value="<?= $student['phone'] ?>" required>
    <textarea name="address" required><?= $student['address'] ?></textarea>
    <button type="submit">Update Student</button>
</form>
