<?php
session_start();
$_SESSION['user_id'] = 1; // TEMPORARY: For testing purposes only, simulating a logged-in user

// Create a connection to the MySQL database
$conn = new mysqli("localhost", "root", "", "rentmycar");

// Check for a connection error and terminate if failed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure caravan ID is provided via GET or POST, otherwise stop execution
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    die("No caravan ID provided.");
}

// Get the current user's ID from the session
$user_id = $_SESSION['user_id'];

// Get the vehicle ID from GET or POST request and cast to integer for safety
$vehicle_id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];

// If form is submitted and deletion is confirmed, proceed with deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // SQL query to delete caravan only if it belongs to the current user
    $delete = "DELETE FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
    
    // Execute the deletion query
    if (mysqli_query($conn, $delete)) {
        // Redirect to 'my_caravans' page after successful deletion
        header("Location: my_caravans.php");
        exit;
    } else {
        // Display error message if deletion fails
        echo "Error deleting caravan: " . mysqli_error($conn);
        exit;
    }
}

// Query the database to fetch details of the caravan for confirmation display
$query = "SELECT * FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

// If the caravan doesn't exist or doesn't belong to the user, stop execution
if (!$result || mysqli_num_rows($result) === 0) {
    die("Caravan not found or does not belong to this user.");
}

// Fetch caravan details into associative array
$caravan = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Caravans - RentMyCaravan</title>
    <link rel="stylesheet" href="css/delete_caravan.css"> <!-- Link to external CSS -->
</head>
<body>
<div class="container">
    <header>
        <!-- Site branding -->
        <div class="logo-box">150 × 100</div>
        <h1>RentMyCaravan</h1>
        <div class="logo-box">150 × 100</div>
    </header>

    <!-- Navigation menu -->
    <nav>
        <a href="welcome.php">Home</a>
        <a href="add_caravan.php">Add Caravan</a>
        <a href="my_caravan.php" class="active">My Caravans</a>
        <a href="about.php">About us</a>
        <a href="logout.php">Logout</a>
    </nav>

    <!-- Main content -->
    <main>
        <h2>Delete Caravan</h2>

        <!-- Display caravan summary -->
        <div class="caravan-summary">
            <h3><?php echo $caravan['vehicle_make'] . ' ' . $caravan['vehicle_model']; ?></h3>
            <p><strong>Location:</strong> <?php echo $caravan['location']; ?></p>
            <p><strong>Year:</strong> <?php echo $caravan['year']; ?></p>
        </div>

        <p>Are you sure you want to delete this caravan?</p>

        <!-- Form to confirm deletion -->
        <form method="post" onsubmit="return confirmDelete();" class="delete-form">
            <!-- Pass caravan ID as a hidden field -->
            <input type="hidden" name="id" value="<?php echo $caravan['vehicle_id']; ?>">

            <!-- Buttons to confirm or cancel deletion -->
            <div class="button-group">
                <button type="submit" name="confirm_delete" class="delete-button">Delete</button>
                <button type="button" onclick="window.location.href='my_caravans.php'" class="cancel-button">Cancel</button>
            </div>
        </form>
    </main>
</div>

<script>
    // Highlight the current page in the nav bar
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

    // Optional: Attach cancel button handler (redundant if using inline onclick above)
    document.getElementById("cancelDeleteBtn")?.addEventListener("click", function () {
        window.location.href = "my_caravans.php";
    });

    // Optional: Confirm deletion popup
    function confirmDelete() {
        return confirm("Are you sure you want to permanently delete this caravan?");
    }
</script>

</body>
</html>
