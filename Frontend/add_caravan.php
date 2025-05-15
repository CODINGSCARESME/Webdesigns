<?php
session_start();

// Temporary login simulation
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

$conn = new mysqli("localhost", "root", "", "rentmycar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $make = clean_input($_POST['vehicle_make']);
    $model = clean_input($_POST['vehicle_model']);
    $bodytype = clean_input($_POST['vehicle_bodytype']);
    $fuel = clean_input($_POST['fuel_type']);
    $mileage = clean_input($_POST['mileage']);
    $location = clean_input($_POST['location']);
    $year = clean_input($_POST['year']);
    $doors = clean_input($_POST['num_doors']);
    $video = clean_input($_POST['video_url']);

    $upload_dir = __DIR__ . "/uploads/";
$img_name = uniqid() . "_" . basename($_FILES["image_url"]["name"]);
$img_path = $upload_dir . $img_name;
$relative_path = "uploads/" . $img_name;

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $img_path)) {
    $stmt = $conn->prepare("INSERT INTO vehicle_details (user_id, vehicle_make, vehicle_model, vehicle_bodytype, fuel_type, mileage, location, year, num_doors, video_url, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssss", $user_id, $make, $model, $bodytype, $fuel, $mileage, $location, $year, $doors, $video, $relative_path);

    if ($stmt->execute()) {
        echo "<script>alert('Caravan added successfully!'); window.location.href = 'welcome.php';</script>";
    } else {
        echo "<script>alert('Database error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
} else {
    echo "<script>alert('Image move failed: check folder location and permissions.');</script>";
}

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Caravan - RentMyCaravan</title>
    <link rel="stylesheet" href="css/add_caravan.css">
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
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
        <a href="add_caravan.php" class="active">Upload</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact</a>
    </nav>

    <div class="form-section">
        <h2>Add Caravan Details Here</h2>
        <form method="POST" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateCaravanForm(this);">
            <label>Vehicle Make*</label>
            <input type="text" name="vehicle_make" placeholder="e.g. Bailey" required>

            <label>Model*</label>
            <input type="text" name="vehicle_model" placeholder="e.g. Pegasus Grande" required>

            <label>Body type*</label>
            <input type="text" name="vehicle_bodytype" placeholder="e.g. Touring" required>

            <label>Fuel type*</label>
            <input type="text" name="fuel_type" placeholder="e.g. Diesel" required>

            <label>Mileage*</label>
            <input type="text" name="mileage" placeholder="e.g. 20,000" required>

            <label>Location*</label>
            <input type="text" name="location" placeholder="e.g. Cardiff" required>

            <label>Year*</label>
            <input type="text" name="year" placeholder="e.g. 2022" required>

            <label>Number of doors*</label>
            <input type="number" name="num_doors" placeholder="e.g. 2" required>

            <label>Image Upload*</label>
            <input type="file" name="image_url" required>

            <label>Video URL</label>
            <input type="url" name="video_url" placeholder="optional YouTube link">

            <button type="submit" class="register-btn">Add Caravan</button>
        </form>
    </div>
</div>
<script src="js/caravan.js"></script>
</body>
</html>
