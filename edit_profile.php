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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $pdo->prepare("UPDATE students SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, address = :address WHERE user_id = :user_id");
    $stmt->execute([
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'user_id' => $user_id
    ]);

    header('Location: student_dashboard.php');
    exit();
}
?>

<form method="POST">
    <input type="text" name="first_name" value="<?= $student['first_name'] ?>" required>
    <input type="text" name="last_name" value="<?= $student['last_name'] ?>" required>
    <input type="email" name="email" value="<?= $student['email'] ?>" required>
    <input type="text" name="phone" value="<?= $student['phone'] ?>" required>
    <textarea name="address" required><?= $student['address'] ?></textarea>
    <button type="submit">Update Profile</button>
</form>
