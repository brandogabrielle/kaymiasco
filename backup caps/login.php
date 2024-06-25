<?php
session_start();
require 'config.php'; // This file should contain your database connection code

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a connection to the database
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize user input
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    // Fetch user data from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a new session and save user info
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Redirect to a welcome page or dashboard
            header('Location: dashboardd.php');
            exit();
        } else {
            // Invalid password
            echo "Invalid username or password.";
        }
    } else {
        // Invalid username
        echo "Invalid username or password.";
    }

    $conn->close();
}
?>
