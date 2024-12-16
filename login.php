<?php
session_start();

include('connection.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($conn) {
        $query = "SELECT * FROM users WHERE username = ?";
        
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $username); 
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];  // Admin or Student
                    header("Location: dashboard.php");
                    exit();
                } else {
                    echo "Invalid credentials.";
                }
            } else {
                echo "User not found.";
            }

            $stmt->close();
        } else {
            echo "Failed to prepare statement.";
        }
    } else {
        echo "Database connection failed.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>
