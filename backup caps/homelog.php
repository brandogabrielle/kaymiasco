<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Streak Login</title>
    <link rel="stylesheet" href="homelog.css">
</head>
<body>
    <div class="top-section">
        <!-- Content for the top section goes here -->
    </div>
    <div class="main-content">
    <div class="gray-line"></div>
        <!-- Main content goes here -->
    <div class="center">
    <button id="show-login">Login</button>
    </div>
    <div class="popup"> 
    <div class="close-btn">&times;</div>
    <form action="login.php" method="post">
        <div class="form-element">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required><br>
        </div>
        <div class="form-element">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required><br>
        </div>
        <div class="form-element">
        <button type="submit">Login</button>
        </div>
    </form>
    <script src="loginjs.js"></script>
    <?php
    session_start();
    if (isset($_SESSION['message'])) {
        echo '<p>' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    ?>
</body>
</html>
