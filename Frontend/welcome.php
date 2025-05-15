<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$db = 'rentmycar';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db); 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <link rel="stylesheet" href="css/welcome.css">
</head>
<body>
<div class="container">
    <header>
        <div class="logo-box">150 × 100</div>
        <h1>RentMyCaravan</h1>
        <div class="logo-box">150 × 100</div>
    </header>

  <nav>
  <a href="index.php">Home</a>
  <a href="add_caravan.php">Add Caravan</a>
  <a href="my_caravan.php">My Caravans</a>
  <a href="about.php">About us</a>
  <a href="logout.php">Logout</a>
  </nav>

    <div class="welcome-box">
        <p>📢 <strong>Welcome, <?php echo htmlspecialchars($username); ?>!</strong>
        | You have <strong><?php echo $caravan_count; ?></strong> caravans listed.</p>
    </div>

    <h2 class="section-title">My Caravans</h2>
    <div class="caravan-grid">
        <?php foreach ($caravans as $caravan): ?>
            <div class="caravan-card">
                <img src="<?php echo htmlspecialchars($caravan['image_url']); ?>" alt="Caravan Image" width="160" height="130">
                <p><?php echo htmlspecialchars($caravan['vehicle_make']); ?></p>
                <a href="edit_caravan.php?id=<?php echo $caravan['vehicle_id']; ?>" class="btn">Edit</a>
                <a href="delete_caravan.php?id=<?php echo $caravan['vehicle_id']; ?>" class="btn delete">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

