<?php
session_start();

include('connection.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['role'];

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$student_data = [];
if ($user_role == 'Student') {
    $query = "SELECT * FROM students WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student_data = $result->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    
    <?php if ($user_role == 'Admin'): ?>
        <h2>Admin Dashboard</h2>
        <p>Manage students, grades, and attendance here.</p>

        <ul>
            <li><a href="admin_dashboard.php">Manage Students</a></li>
            <li><a href="assign_grade.php">Assign Grades</a></li>
            <li><a href="mark_attendance.php">Mark Attendance</a></li>
            <li><a href="generate_reports.php">Generate Reports</a></li>
        </ul>
        
    <?php elseif ($user_role == 'Student'): ?>
        <h2>Student Dashboard</h2>
        <p>Your personal information and grades.</p>

        <h3>Personal Details</h3>
        <p>Name: <?php echo htmlspecialchars($student_data['name']); ?></p>
        <p>Email: <?php echo htmlspecialchars($student_data['email']); ?></p>
        
        <h3>Your Grades</h3>
        <ul>
            <?php
            $query = "SELECT * FROM grades WHERE student_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($grade = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($grade['subject_name']) . ": " . htmlspecialchars($grade['grade']) . "</li>";
            }
            ?>
        </ul>

        <h3>Your Subjects</h3>
        <ul>
            <?php
            $query = "SELECT * FROM subjects WHERE student_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($subject = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($subject['subject_name']) . "</li>";
            }
            ?>
        </ul>
    <?php endif; ?>
    
    <br><br>
    <a href="logout.php">Logout</a>
</body>
</html>
<?php
$conn->close();
?>
