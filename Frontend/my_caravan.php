<?php
session_start();

// Temporary login simulation: sets user_id in session if not already set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

// Create connection to the database
$conn = new mysqli("localhost", "root", "", "rentmycar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Query to get all caravan details belonging to the logged-in user
$query = "SELECT * FROM vehicle_details WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

// If query fails, show error and stop
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Caravans - RentMyCaravan</title>
    <link rel="stylesheet" href="css/my_caravan.css" /> <!-- External CSS for styling -->
</head>
<body>
<div class="container">
    <header>
        <div class="logo-box">150 × 100</div> <!-- Placeholder logo area -->
        <h1>RentMyCaravan</h1>
        <div class="logo-box">150 × 100</div> <!-- Placeholder logo area -->
    </header>

    <nav>
        <!-- Navigation menu -->
        <a href="welcome.php">Home</a>
        <a href="add_caravan.php">Add Caravan</a>
        <a href="my_caravan.php" class="active">My Caravans</a> <!-- Active/current page -->
        <a href="about.php">About us</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <h2>My Caravans</h2>

        <!-- Show message if user has no caravans listed -->
        <?php if (mysqli_num_rows($result) === 0): ?>
            <div class="empty-message">You currently have no caravans listed.</div>
        <?php else: ?>
            <!-- Loop through each caravan and display details -->
            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <div class="caravan-card">
                    <!-- Caravan image -->
                    <img src="<?php echo $row['image_url']; ?>" width="150" height="100" alt="Caravan Image">

                    <div class="info">
                        <!-- Caravan basic info -->
                        <h3><?php echo $row['vehicle_make'] . ' ' . $row['vehicle_model']; ?></h3>
                        <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                        <p><strong>Year:</strong> <?php echo $row['year']; ?></p>
                        <p><strong>Body Type:</strong> <?php echo $row['vehicle_bodytype']; ?></p>
                        <p><strong>Fuel:</strong> <?php echo $row['fuel_type']; ?></p>
                        <p><strong>Mileage:</strong> <?php echo $row['mileage']; ?></p>
                        <p><strong>Doors:</strong> <?php echo $row['num_doors']; ?></p>
                    </div>

                    <div class="actions">
                        <!-- Edit button: sends vehicle_id via GET to edit_caravan.php -->
                        <form action="edit_caravan.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['vehicle_id']); ?>">
                            <button type="submit" class="edit-button">Edit</button>
                        </form>

                        <!-- Delete button: sends vehicle_id via GET to delete_caravan.php -->
                        <form action="delete_caravan.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['vehicle_id']; ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>

                        <!-- Summary button: sends vehicle_id via GET to caravan_summary.php -->
                        <form action="caravan_summary.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['vehicle_id']); ?>">
                            <button type="submit" class="summary-button">Summary</button>
                        </form>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php endif; ?>

    </main>
</div>

<script>
    // Highlight the active nav link based on current URL
    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll("nav a");
        const currentPage = window.location.pathname.split("/").pop();

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
