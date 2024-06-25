<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Not logged in";
    exit();
}

$user_id = $_SESSION['user_id'];

$servername = "localhost";
$db_username = "root"; // Replace with your MySQL username
$db_password = ""; // Replace with your MySQL password
$dbname = "registration_system"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agenda_date']) && isset($_POST['agenda_text'])) {
    $agenda_date = $_POST['agenda_date'];
    $agenda_text = $_POST['agenda_text'];

    $stmt = $conn->prepare("INSERT INTO agendas (user_id, agenda_date, agenda_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $agenda_date, $agenda_text);

    if ($stmt->execute()) {
        echo "Agenda saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request";
}

$conn->close();
?>
