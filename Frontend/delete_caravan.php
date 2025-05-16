<?php
session_start();
$_SESSION['user_id'] = 1; // TEMP for testing - simulating a logged-in user

// Connect to the database
$conn = new mysqli("localhost", "root", "", "rentmycar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if caravan ID is provided either via GET or POST
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    die("No caravan ID provided.");
}

$user_id = $_SESSION['user_id']; // Get current user ID from session
// Determine vehicle ID from GET or POST request
$vehicle_id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];

// Handle form submission for deleting the caravan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // SQL to delete caravan belonging to this user with specified vehicle_id
    $delete = "DELETE FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
    if (mysqli_query($conn, $delete)) {
        // Redirect to user's caravan list after successful deletion
        header("Location: my_caravan.php");
        exit;
    } else {
        // Output error if deletion fails
        echo "Error deleting caravan: " . mysqli_error($conn);
        exit;
    }
}

// Fetch the caravan details to display in the delete confirmation page
$query = "SELECT * FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

// Check if caravan exists and belongs to the user
if (!$result || mysqli_num_rows($result) === 0) {
    die("Caravan not found or does not belong to this user.");
}

$caravan = mysqli_fetch_assoc($result); // Fetch caravan data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Caravans - RentMyCaravan</title>
    <link rel="stylesheet" href="css/delete_caravan.css"> <!-- CSS for styling -->
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
        <h2>Delete Caravan</h2>
        <div class="caravan-summary">
            <!-- Display caravan details for confirmation -->
            <h3><?php echo $caravan['vehicle_make'] . ' ' . $caravan['vehicle_model']; ?></h3>
            <p><strong>Location:</strong> <?php echo $caravan['location']; ?></p>
            <p><strong>Year:</strong> <?php echo $caravan['year']; ?></p>
        </div>

        <p>Are you sure you want to delete this caravan?</p>

        <!-- Delete confirmation form -->
        <form method="post" onsubmit="return confirmDelete();" class="delete-form">
            <!-- Hidden input to send vehicle ID -->
            <input type="hidden" name="id" value="<?php echo $caravan['vehicle_id']; ?>">

            <div class="button-group">
                <!-- Delete button triggers form submission -->
                <button type="submit" name="confirm_delete" class="delete-button">Delete</button>
                <!-- Cancel button redirects back to My Caravans page -->
                <button type="button" onclick="window.location.href='my_caravan.php'" class="cancel-button">Cancel</button>
            </div>
        </form>
    </main>
</div>

<script>
    // Highlight the active nav link based on current page URL
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

    
    document.getElementById("cancelDeleteBtn").addEventListener("click", function () {
        window.location.href = "my_caravan.php";
    });

</script>

</body>
</html>
