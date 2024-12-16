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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $grade = $_POST['grade'];

    if (!is_numeric($grade) || $grade < 0 || $grade > 100) {
        echo "Invalid grade. Please enter a number between 0 and 100.";
    } else {
        $query_check_grade = "SELECT * FROM grades WHERE student_id = ? AND subject_id = ?";
        $stmt_check = $conn->prepare($query_check_grade);
        $stmt_check->bind_param('ii', $student_id, $subject_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $query_update_grade = "UPDATE grades SET grade = ? WHERE student_id = ? AND subject_id = ?";
            $stmt_update = $conn->prepare($query_update_grade);
            $stmt_update->bind_param('dii', $grade, $student_id, $subject_id);  // 'd' for double (numeric)
            $stmt_update->execute();
            echo "Grade updated successfully!";
        } else {
            $query_insert_grade = "INSERT INTO grades (student_id, subject_id, grade) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($query_insert_grade);
            $stmt_insert->bind_param('iid', $student_id, $subject_id, $grade);  // 'i' for integer, 'd' for double
            $stmt_insert->execute();
            echo "Grade assigned successfully!";
        }
    }
}
?>

<h1>Assign Grade to Student</h1>

<form method="POST">
    <label for="student_id">Select Student:</label>
    <select name="student_id" id="student_id" required>
        <?php while ($student = $students_result->fetch_assoc()): ?>
        <option value="<?= $student['id'] ?>"><?= $student['first_name'] . ' ' . $student['last_name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="subject_id">Select Subject:</label>
    <select name="subject_id" id="subject_id" required>
        <?php while ($subject = $subjects_result->fetch_assoc()): ?>
        <option value="<?= $subject['id'] ?>"><?= $subject['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="grade">Enter Grade (0-100):</label>
    <input type="number" name="grade" id="grade" required min="0" max="100" step="0.01"><br><br>

    <input type="submit" value="Assign Grade">
</form>

<a href="admin_dashboard.php">Back to Admin Dashboard</a>
