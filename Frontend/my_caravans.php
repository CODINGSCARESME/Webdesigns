<?php
session_start();

// TEMP: simulate login until login system is ready
$_SESSION['user_id'] = 1;

include 'db_connect.php'; // your DB connection file

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM vehicle_details WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Caravans - RentMyCaravan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>RentMyCaravan</h1>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a href="add_caravan.php">Add Caravan</a>
        <a href="my_caravans.php" class="active">My Caravans</a>
        <a href="about.php">About us</a>
        <a href="logout.php">Logout</a>
    </nav>

    <main>
        <h2>My Caravans</h2>

        <?php if (mysqli_num_rows($result) === 0): ?>
            <div class="empty-message">You currently have no caravans listed.</div>
        <?php else: ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <div class="caravan-card">
                    <img src="<?php echo $row['image_url']; ?>" width="150" height="100">
                    <div class="info">
                        <h3><?php echo $row['vehicle_make'] . ' ' . $row['vehicle_model']; ?></h3>
                        <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                        <p><strong>Year:</strong> <?php echo $row['year']; ?></p>
                        <p><strong>Body Type:</strong> <?php echo $row['vehicle_bodytype']; ?></p>
                        <p><strong>Fuel:</strong> <?php echo $row['fuel_type']; ?></p>
                        <p><strong>Mileage:</strong> <?php echo $row['mileage']; ?></p>
                        <p><strong>Doors:</strong> <?php echo $row['num_doors']; ?></p>
                    </div>

                    <div class="actions">
                        <form action="edit_caravan.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['vehicle_id']); ?>">
                            <button type="submit" class="edit-button">Edit</button>
                        </form>

                        <form action="delete_caravan.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['vehicle_id']; ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>

                        <form action="caravan_summary.php" method="get" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['vehicle_id']); ?>">
                            <button type="submit" class="summary-button">Summary</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

    </main>

    <script>
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