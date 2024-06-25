<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming JSON data is sent
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract data from JSON
    $user_id = $data['user_id'];
    $session_start = $data['session_start'];
    $session_end = $data['session_end'];
    $breaks_taken = $data['breaks_taken'];

    // Perform database operations to insert data
    $servername = "localhost";
    $username = "root"; // Replace with your MySQL username
    $password = ""; // Replace with your MySQL password
    $dbname = "registration_system"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute SQL statement
    $sql = "INSERT INTO pomodoro (user_id, session_start, session_end, breaks_taken) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $user_id, $session_start, $session_end, $breaks_taken);

    if ($stmt->execute()) {
        echo "Pomodoro data saved successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Method not allowed"; // Handle if method is not POST
}
?>
