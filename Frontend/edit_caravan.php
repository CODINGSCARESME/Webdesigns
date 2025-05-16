<?php
session_start();

// Temporary login simulation - set user_id in session if not already set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "rentmycar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Fetch caravan details for the logged-in user
$query = "SELECT * FROM vehicle_details WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

// If query fails, stop and show error
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Get the first caravan from the result set
$caravan = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Caravan - RentMyCaravan</title>
    <link rel="stylesheet" href="css/edit_caravan.css" /> <!-- Link to CSS styles -->
</head>
<body>
<div class="container">
    <header>
        <div class="logo-box">150 × 100</div> <!-- Placeholder for logo -->
        <h1>RentMyCaravan</h1>
        <div class="logo-box">150 × 100</div> <!-- Placeholder for logo -->
    </header>

    <nav>
        <!-- Navigation menu -->
        <a href="welcome.php">Home</a>
        <a href="add_caravan.php">Add Caravan</a>
        <a href="my_caravan.php" class="active">My Caravans</a> <!-- Current page -->
        <a href="about.php">About us</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <h2>Edit Caravan</h2>

        <!-- Edit form submits to update_caravan.php using POST method -->
        <form action="update_caravan.php" method="POST" enctype="multipart/form-data">
            <!-- Hidden input to pass the vehicle ID -->
            <input type="hidden" name="vehicle_id" value="<?php echo $caravan['vehicle_id']; ?>">

            <!-- Vehicle Make input field -->
            <label>Vehicle Make:</label>
            <input type="text" name="vehicle_make" value="<?php echo htmlspecialchars($caravan['vehicle_make']); ?>">

            <!-- Vehicle Model input field -->
            <label>Vehicle Model:</label>
            <input type="text" name="vehicle_model" value="<?php echo htmlspecialchars($caravan['vehicle_model']); ?>">

            <!-- Body Type input field -->
            <label>Body Type:</label>
            <input type="text" name="body_type" value="<?php echo htmlspecialchars($caravan['vehicle_bodytype']); ?>">

            <!-- Fuel Type input field -->
            <label>Fuel Type:</label>
            <input type="text" name="fuel_type" value="<?php echo htmlspecialchars($caravan['fuel_type']); ?>">

            <!-- Mileage input field -->
            <label>Mileage:</label>
            <input type="text" name="mileage" value="<?php echo htmlspecialchars($caravan['mileage']); ?>">

            <!-- Location input field -->
            <label>Location:</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($caravan['location']); ?>">

            <!-- Year input field -->
            <label>Year:</label>
            <input type="text" name="year" value="<?php echo htmlspecialchars($caravan['year']); ?>">

            <!-- Number of Doors input field -->
            <label>Number of Doors:</label>
            <input type="text" name="num_doors" value="<?php echo htmlspecialchars($caravan['num_doors']); ?>">

            <!-- Video URL input field -->
            <label>Video Url:</label>
            <input type="text" name="video_url" value="<?php echo htmlspecialchars($caravan['video_url']); ?>">

            <!-- Image URL input field for uploading a new file -->
            <label>Image Url:</label>
            <input type="file" name="image_url">

            <!-- Save button to submit the form -->
            <button type="submit">Save</button>

            <!-- Cancel button redirects back to My Caravans page -->
            <button type="button" onclick="window.location.href='my_caravans.php'">Cancel</button>
        </form>
    </main>
</div>

<script>
    // Highlight the active navigation link based on current page
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
