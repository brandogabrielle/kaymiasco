<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: homelog.php'); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

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
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- ICONS -->
    <script src="https://kit.fontawesome.com/6f3103b13c.js" crossorigin="anonymous"></script>
    <script src="c.js"></script>

    <!-- POMODORO SCRIPT -->
    <script src="pp.js"></script>

    <script>
        // Include userId and username from PHP session
        let userId = <?php echo json_encode($_SESSION['user_id']); ?>;
        let username = <?php echo json_encode($_SESSION['username']); ?>;
    </script>
</head>
<body>
    <div class="con1">
        <div class="q">
            <h1 class="e">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <a href="logout.php" class="e">Logout</a>
        </div>
        <div>
            <h2 class="q">Your Data</h2>
            <!-- Display user data if needed -->
        </div>
    </div>

    <div class="w"></div>

    <!-- Content Below -->

    <div class="r">
        <div><!-- Calendar -->
            <div class="wrapper">
                <header>
                    <div class="current-date"></div>
                    <div class="icons">
                        <span id="prev">&#8249;</span>
                        <span id="next">&#8250;</span>
                    </div>
                </header>
                <div class="calendar">
                    <ul class="weeks">
                        <li>Sun</li>
                        <li>Mon</li>
                        <li>Tue</li>
                        <li>Wed</li>
                        <li>Thu</li>
                        <li>Fri</li>
                        <li>Sat</li>
                    </ul>
                    <ul class="days" id="calendar"></ul>
                </div>
            </div>
            <div id="agenda-popup" class="hidden"></div>
            <!-- Modal for adding agenda -->
            <div id="modal" class="modal hidden">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Add Agenda</h2>
                    <form id="agenda-form">
                        <label for="agenda-text">Agenda:</label>
                        <input type="text" id="agenda-text" required>
                        <input type="hidden" id="agenda-date">
                        <button type="submit">Add</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Pomodoro Timer Section -->
        <section>
            <div class="container">
                <h1>Pomodoro</h1>

                <div class="painel">
                    <p id="work">Work</p>
                    <p id="break">Break</p>
                </div>

                <div class="timer">
                    <div class="circle">
                        <div class="time">
                            <p id="minutes">25</p>
                            <p>:</p>
                            <p id="seconds">00</p>
                        </div>
                    </div>
                </div>

                <div class="controls">
                    <button id="start" onclick="start()"><i class="fa-solid fa-play"></i></button>
                    <button id="reset" onclick="resetTimer()"><i class="fa-solid fa-arrow-rotate-left"></i></button>
                </div>
            </div>
        </section>
    </div>
    </div>
</body>
</html>
