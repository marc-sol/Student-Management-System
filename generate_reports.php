<?php
session_start();
include('connection.php');

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    if ($report_type == 'attendance') {
        $query = "
            SELECT students.first_name, students.last_name, subjects.name AS subject_name, attendance.date, attendance.status
            FROM attendance
            JOIN students ON attendance.student_id = students.id
            JOIN subjects ON attendance.subject_id = subjects.id
            WHERE attendance.date BETWEEN ? AND ?
            ORDER BY attendance.date, students.first_name, subjects.name
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        
        echo "<h1>Attendance Report (from $start_date to $end_date)</h1>";
        echo "<table border='1'>
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
                    <td>" . $row['subject_name'] . "</td>
                    <td>" . $row['date'] . "</td>
                    <td>" . $row['status'] . "</td>
                </tr>";
        }
        
        echo "</table>";
        
    } elseif ($report_type == 'grades') {
        $query = "
            SELECT students.first_name, students.last_name, subjects.name AS subject_name, grades.grade
            FROM grades
            JOIN students ON grades.student_id = students.id
            JOIN subjects ON grades.subject_id = subjects.id
        ";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        
        echo "<h1>Grades Report</h1>";
        echo "<table border='1'>
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
                    <td>" . $row['subject_name'] . "</td>
                    <td>" . $row['grade'] . "</td>
                </tr>";
        }
        
        echo "</table>";
        
    } else {
        echo "Invalid report type.";
    }
}
?>

<h1>Generate Report</h1>

<form method="POST">
    <label for="report_type">Select Report Type:</label>
    <select name="report_type" id="report_type" required>
        <option value="attendance">Attendance Report</option>
        <option value="grades">Grades Report</option>
    </select><br><br>

    <div id="attendance_dates">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" required><br><br>
        
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required><br><br>
    </div>

    <input type="submit" value="Generate Report">
</form>

<script>
    const reportTypeSelect = document.getElementById('report_type');
    const attendanceDatesDiv = document.getElementById('attendance_dates');

    if (reportTypeSelect.value === 'grades') {
        attendanceDatesDiv.style.display = 'none';
    }

    reportTypeSelect.addEventListener('change', function() {
        if (this.value === 'attendance') {
            attendanceDatesDiv.style.display = 'block';
        } else {
            attendanceDatesDiv.style.display = 'none';
        }
    });
</script>
