<?php
//Enable error reporting bug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = "";
// Database connection details
$host = 'localhost';
$db = 'rentmycar';
$user = 'root';
$pass = '';

// Create a new database connection using mysqli
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if the form is submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST["username"]);
    $password = clean_input($_POST["password"]);

    // Prepare a SQL statement to select user ID and hashed password based on the username
    $stmt = $conn->prepare("SELECT user_id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    // Execute the prepared statement
    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            // Use password_verify to compare hashed password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                header("Location: welcome.php");
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }

        $stmt->close();
    } else {
        $error = "Query error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - RentMyCaravan</title>
    <!-- Link to external CSS for styling -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="container">
    <!-- Website Header Section -->
    <header>
        <div class="logo-box">150 × 100</div>
        <h1>RentMyCaravan</h1>
        <div class="logo-box">150 × 100</div>
    </header>

    <!-- Navigation Menu -->
    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php" class="active">Login</a>
        <a href="add_caravan.php">Upload</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>

    </nav>

    <!-- Main Login Content Area -->
    <div class="login-content">
    <div class="login-image">
        <img src="img/login-placeholder.jpg" alt="Login Image" width="300" height="260">
    </div>

    <!-- Right Side: Login Form Section -->
    <div class="form-section">
        <h3>🔐 Login to your account</h3>
        
        <!-- Display error message if exists -->
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <!-- Login Form -->
        <form method="POST" action="login.php">
            <!-- Username Field -->
            <label>Username</label>
            <input type="text" name="username" required>

            <!-- Password Field -->
            <label>Password</label>
            <input type="password" name="password" required>

            <!-- Submit Button -->
            <button type="submit" class="register-btn">Submit</button>
        </form>
        <!-- Registration Link for New Users -->
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
</body>
</html>