<?php
//Enable error reporting bug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start or resume session
session_start();

// Check if the user is logged in; if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
// Database connection details
$host = 'localhost';
$db = 'rentmycar';
$user = 'root';
$pass = '';

// Create a new database connection
$conn = new mysqli($host, $user, $pass, $db); 

// Check if connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current user ID and username from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch user's caravans
$stmt = $conn->prepare("SELECT vehicle_id, vehicle_make, image_url FROM vehicle_details WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$caravans = $result->fetch_all(MYSQLI_ASSOC);
$caravan_count = count($caravans);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome - RentMyCaravan</title>
    <!-- Link to external CSS for page styling -->
    <link rel="stylesheet" href="css/welcome.css">
</head>
<body>
<div class="container">
    <!-- Website header section -->
    <header>
        <div class="logo-box">150 × 100</div>
        <h1>RentMyCaravan</h1>
        <div class="logo-box">150 × 100</div>
    </header>

  <!-- Navigation menu -->
  <nav>
  <a href="index.php">Home</a>
  <a href="add_caravan.php">Add Caravan</a>
  <a href="my_caravan.php">My Caravans</a>
  <a href="about.php">About us</a>
  <a href="logout.php">Logout</a>
  </nav>

  <!-- Welcome message showing username and caravan count -->
    <div class="welcome-box">
        <p>📢 <strong>Welcome, <?php echo htmlspecialchars($username); ?>!</strong>
        | You have <strong><?php echo $caravan_count; ?></strong> caravans listed.</p>
    </div>

    <!-- Section displaying user's caravans -->
    <h2 class="section-title">My Caravans</h2>
    <div class="caravan-grid">
        <!-- Loop through user's caravans and display each -->
        <?php foreach ($caravans as $caravan): ?>
            <div class="caravan-card">
                <!-- Caravan image -->
                <img src="<?php echo htmlspecialchars($caravan['image_url']); ?>" alt="Caravan Image" width="160" height="130">
                <!-- Caravan make -->
                <p><?php echo htmlspecialchars($caravan['vehicle_make']); ?></p>

                <!-- Edit and Delete action buttons -->
                <a href="edit_caravan.php?id=<?php echo $caravan['vehicle_id']; ?>" class="btn">Edit</a>
                <a href="delete_caravan.php?id=<?php echo $caravan['vehicle_id']; ?>" class="btn delete">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

