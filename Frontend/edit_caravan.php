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

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM vehicle_details WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$caravan = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Caravan - RentMyCaravan</title>
    <link rel="stylesheet" href="edit_caravan.css">
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
    <a href="my_caravans.php" class="active">My Caravans</a>
    <a href="about.php">About us</a>
    <a href="logout.php">Logout</a>
</nav>
    <main>
        <h2>Edit Caravan</h2>

        <form action="update_caravan.php" method="POST">
            <input type="hidden" name="vehicle_id" value="<?php echo $caravan['vehicle_id']; ?>">

            <label>Vehicle Make:</label>
            <input type="text" name="vehicle_make" value="<?php echo $caravan['vehicle_make']; ?>">

            <label>Vehicle Model:</label>
            <input type="text" name="vehicle_model" value="<?php echo $caravan['vehicle_model']; ?>">

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

            <label>Video Url:</label>
            <input type="text" name="video_url" value="<?php echo $caravan['video_url']; ?>">

            <label>Image Url:</label>
            <input type="text" name="image_url" value="<?php echo $caravan['image_url']; ?>">

            <button type="submit">Save</button>
            <button type="button" onclick="window.location.href='my_caravans.php'">Cancel</button>
        </form>
    </main>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const currentPage = window.location.pathname.split("/").pop();
            const links = document.querySelectorAll("nav a");

            links.forEach(link => {
                if (link.getAttribute("href") === currentPage) {
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });
        });
    </script>

</body>
</html>