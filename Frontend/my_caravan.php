<?php
session_start();

// Simulate login for testing (replace with real auth logic)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

$conn = new mysqli("localhost", "root", "", "rentmycar");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle deletion if triggered via GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $vehicle_id = (int)$_GET['id'];
    $delete = "DELETE FROM vehicle_details WHERE vehicle_id = $vehicle_id AND user_id = $user_id";
    if (mysqli_query($conn, $delete)) {
        $message = "Caravan deleted successfully.";
    } else {
        $message = "Error deleting caravan: " . mysqli_error($conn);
    }
}

// Fetch caravans
$query = "SELECT * FROM vehicle_details WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Caravans - RentMyCaravan</title>
    <link rel="stylesheet" href="css/my_caravan.css">
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
        <a href="my_caravans.php" class="active">My Caravans</a>
        <a href="about.php">About us</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <h2>My Caravans</h2>

        <?php if ($message): ?>
            <p style="color: green;"><strong><?php echo $message; ?></strong></p>
        <?php endif; ?>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <div class="empty-message">You currently have no caravans listed.</div>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="caravan-card">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" width="150" height="100" alt="Caravan Image">
                    <div class="info">
                        <h3><?php echo htmlspecialchars($row['vehicle_make'] . ' ' . $row['vehicle_model']); ?></h3>
                        <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                        <p><strong>Year:</strong> <?php echo htmlspecialchars($row['year']); ?></p>
                        <p><strong>Body Type:</strong> <?php echo htmlspecialchars($row['vehicle_bodytype']); ?></p>
                        <p><strong>Fuel:</strong> <?php echo htmlspecialchars($row['fuel_type']); ?></p>
                        <p><strong>Mileage:</strong> <?php echo htmlspecialchars($row['mileage']); ?></p>
                        <p><strong>Doors:</strong> <?php echo htmlspecialchars($row['num_doors']); ?></p>
                    </div>

                    <div class="actions">
                        <!-- Edit -->
                        <form action="edit_caravan.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['vehicle_id']; ?>">
                            <button type="submit" class="edit-button">Edit</button>
                        </form>

                        <!-- Delete handled in this file -->
                        <form method="get" onsubmit="return confirm('Are you sure you want to delete this caravan?');" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['vehicle_id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>

                        <!-- Summary -->
                        <form action="caravan_summary.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['vehicle_id']; ?>">
                            <button type="submit" class="summary-button">Summary</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </main>
</div>

<script>
    // Highlight current nav item
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
