<?php
session_start();
$_SESSION['user_id'] = 1; // TEMP for testing

$conn = new mysqli("localhost", "root", "", "rentmycar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    die("No caravan ID provided.");
}

$user_id = $_SESSION['user_id'];
$vehicle_id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    // Perform the delete
    $delete = "DELETE FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
    if (mysqli_query($conn, $delete)) {
        header("Location: my_caravans.php");
        exit;
    } else {
        echo "Error deleting caravan: " . mysqli_error($conn);
        exit;
    }
}

// Fetch caravan to display in confirmation
$query = "SELECT * FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die("Caravan not found or does not belong to this user.");
}

$caravan = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Caravans - RentMyCaravan</title>
    <link rel="stylesheet" href="delete_caravan.css">
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
        <h2>Delete Caravan</h2>
        <div class="caravan-summary">
            <h3><?php echo $caravan['vehicle_make'] . ' ' . $caravan['vehicle_model']; ?></h3>
            <p><strong>Location:</strong> <?php echo $caravan['location']; ?></p>
            <p><strong>Year:</strong> <?php echo $caravan['year']; ?></p>
            </div>

            <p>Are you sure you want to delete this caravan?</p>

            <form method="post" onsubmit="return confirmDelete();">
                <input type="hidden" name="id" value="<?php echo $caravan['vehicle_id']; ?>">
                <button type="submit" name="confirm_delete" class="delete-button">Delete</button>
                <button type="button" onclick="window.location.href='my_caravans.php'" class="cancel-button">Cancel</button>
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

        document.getElementById("cancelDeleteBtn").addEventListener("click", function () {
        window.location.href = "my_caravans.php";
        });
        
    </script>

</body>
</html>