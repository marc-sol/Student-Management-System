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
    $subject_id = $_POST['subject_id'];
    $attendance_date = $_POST['attendance_date'];

    foreach ($_POST['attendance'] as $student_id => $status) {
        $query_check_attendance = "SELECT * FROM attendance WHERE student_id = ? AND subject_id = ? AND date = ?";
        $stmt_check = $conn->prepare($query_check_attendance);
        $stmt_check->bind_param('iis', $student_id, $subject_id, $attendance_date);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $query_update_attendance = "UPDATE attendance SET status = ? WHERE student_id = ? AND subject_id = ? AND date = ?";
            $stmt_update = $conn->prepare($query_update_attendance);
            $stmt_update->bind_param('siii', $status, $student_id, $subject_id, $attendance_date);
            $stmt_update->execute();
        } else {
            $query_insert_attendance = "INSERT INTO attendance (student_id, subject_id, date, status) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($query_insert_attendance);
            $stmt_insert->bind_param('iiss', $student_id, $subject_id, $attendance_date, $status);
            $stmt_insert->execute();
        }
    }

    echo "Attendance marked successfully!";
}
?>

<h1>Mark Attendance</h1>

<form method="POST">
    <label for="subject_id">Select Subject:</label>
    <select name="subject_id" id="subject_id" required>
        <?php while ($subject = $subjects_result->fetch_assoc()): ?>
        <option value="<?= $subject['id'] ?>"><?= $subject['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="attendance_date">Select Date:</label>
    <input type="date" name="attendance_date" id="attendance_date" required><br><br>

    <h3>Select Attendance Status for Students:</h3>
    <?php while ($student = $students_result->fetch_assoc()): ?>
        <label for="attendance[<?= $student['id'] ?>]"><?= $student['first_name'] . ' ' . $student['last_name'] ?>:</label>
        <select name="attendance[<?= $student['id'] ?>]" id="attendance[<?= $student['id'] ?>]" required>
            <option value="Present">Present</option>
            <option value="Absent">Absent</option>
        </select><br><br>
    <?php endwhile; ?>

    <input type="submit" value="Mark Attendance">
</form>

<a href="admin_dashboard.php">Back to Admin Dashboard</a>
