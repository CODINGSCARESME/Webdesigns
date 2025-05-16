<?php
session_start();

// Simulate user login (for testing only)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Replace with real login logic in production
}

$conn = new mysqli("localhost", "root", "", "rentmycar");

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$vehicle_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$caravan = null;
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = (int)$_POST['vehicle_id'];
    $vehicle_make = $_POST['vehicle_make'];
    $vehicle_model = $_POST['vehicle_model'];
    $body_type = $_POST['body_type'];
    $fuel_type = $_POST['fuel_type'];
    $mileage = $_POST['mileage'];
    $location = $_POST['location'];
    $year = $_POST['year'];
    $num_doors = $_POST['num_doors'];
    $video_url = $_POST['video_url'];

    // NOTE: File upload handling (basic)
    $image_url = "";
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
        $upload_dir = 'uploads/';
        $image_url = $upload_dir . basename($_FILES['image_url']['name']);
        move_uploaded_file($_FILES['image_url']['tmp_name'], $image_url);
    }

    // Build update query
    $sql = "UPDATE vehicle_details 
            SET vehicle_make=?, vehicle_model=?, vehicle_bodytype=?, fuel_type=?, mileage=?, location=?, year=?, num_doors=?, video_url=?";
    
    if ($image_url !== "") {
        $sql .= ", image_url=?";
    }

    $sql .= " WHERE vehicle_id=? AND user_id=?";

    $stmt = $conn->prepare($sql);

    if ($image_url !== "") {
        $stmt->bind_param("ssssssssssii", $vehicle_make, $vehicle_model, $body_type, $fuel_type, $mileage, $location, $year, $num_doors, $video_url, $image_url, $vehicle_id, $user_id);
    } else {
        $stmt->bind_param("ssssssssssi", $vehicle_make, $vehicle_model, $body_type, $fuel_type, $mileage, $location, $year, $num_doors, $video_url, $vehicle_id, $user_id);
    }

    if ($stmt->execute()) {
        $message = "Caravan updated successfully!";
    } else {
        $message = "Error updating caravan: " . $stmt->error;
    }
}

// Fetch caravan data for display
if ($vehicle_id > 0) {
    $query = "SELECT * FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $caravan = mysqli_fetch_assoc($result);
    } else {
        die("Caravan not found or does not belong to this user.");
    }
} else {
    die("No caravan ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Caravan - RentMyCaravan</title>
    <link rel="stylesheet" href="css/edit_caravan.css">
</head>
<body>
<div class="container">
    <header>
        <div class="logo-box">150 × 100</div>
        <h1>RentMyCaravan</h1>
        <div class="logo-box">150 × 100</div>
    </header>

    <nav>
        <a href="welcome.php">Home</a>
        <a href="add_caravan.php">Add Caravan</a>
        <a href="my_caravan.php" class="active">My Caravans</a>
        <a href="about.php">About us</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <h2>Edit Caravan</h2>
        <?php if ($message): ?>
            <p style="color: green;"><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="vehicle_id" value="<?php echo $caravan['vehicle_id']; ?>">

            <label>Vehicle Make:</label>
            <input type="text" name="vehicle_make" value="<?php echo $caravan['vehicle_make']; ?>" required>

            <label>Vehicle Model:</label>
            <input type="text" name="vehicle_model" value="<?php echo $caravan['vehicle_model']; ?>" required>

            <label>Body Type:</label>
            <input type="text" name="body_type" value="<?php echo $caravan['vehicle_bodytype']; ?>">

            <label>Fuel Type:</label>
            <input type="text" name="fuel_type" value="<?php echo $caravan['fuel_type']; ?>">

            <label>Mileage:</label>
            <input type="text" name="mileage" value="<?php echo $caravan['mileage']; ?>">

            <label>Location:</label>
            <input type="text" name="location" value="<?php echo $caravan['location']; ?>">

            <label>Year:</label>
            <input type="text" name="year" value="<?php echo $caravan['year']; ?>">

            <label>Number of Doors:</label>
            <input type="text" name="num_doors" value="<?php echo $caravan['num_doors']; ?>">

            <label>Video URL:</label>
            <input type="text" name="video_url" value="<?php echo $caravan['video_url']; ?>">

            <label>Current Image:</label><br>
            <?php if (!empty($caravan['image_url'])): ?>
                <img src="<?php echo $caravan['image_url']; ?>" alt="Caravan Image" width="200"><br>
            <?php endif; ?>

            <label>Upload New Image:</label>
            <input type="file" name="image_url">

            <br><br>
            <button type="submit">Save</button>
            <button type="button" onclick="window.location.href='my_caravans.php'">Cancel</button>
        </form>
    </main>
</div>
</body>
</html>
