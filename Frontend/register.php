<?php
//Enable error reporting bug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Database connection details
$host = 'localhost';
$db = 'rentmycar';  
$user = 'root';
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

//check form is submitted using post method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username  = clean_input($_POST['username']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $title     = clean_input($_POST['title']);
    $fullname  = clean_input($_POST['fullname']);
    $gender    = clean_input($_POST['gender']);
    $address1 = clean_input($_POST['address']); 
    $telephone = clean_input($_POST['telephone']);
    $email     = clean_input($_POST['email']);
    $image_url = null;

    $profileName = $_FILES["profile"]["name"] ?? null;

    if ($profileName && $_FILES["profile"]["error"] === 0) {
        $targetDir = "uploads/";
        $uniqueName = uniqid() . "_" . basename($profileName);
        $targetFile = $targetDir . $uniqueName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (getimagesize($_FILES["profile"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
                $image_url = $targetFile;
            } else {
                echo "<script>alert('Image move failed.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image file.');</script>";
        }
    }

    // Prepare SQL statement to insert user data into database
    $stmt = $conn->prepare("INSERT INTO users (username, password, title, fullname, gender, adress1, telephone, email, profile_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $username, $password, $title, $fullname, $gender, $address1, $telephone, $email, $image_url);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration - RentMyCaravan</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<div class="container">
    <header>
        <div class="logo-box">150 × 100</div>
        <h1>RentMyCaravan</h1>
        <div class="logo-box">150 × 100</div>
    </header>

    <!-- Navigation menu starts here -->
    <nav>
    <a href="index.php">Home</a>
    <a href="register.php" class="active">Register</a>
    <a href="login.php">Login</a>
    <a href="add_caravan.php">Upload</a>
    <a href="about.php">About us</a>
    <a href="contact.php">Contact us</a>
</nav>

<!-- Registration Form -->
<div class="register-form">
        <h2>Account Registration Form</h2>
        <p class="intruction">Complete this form and select register button to complete.</p>

        <form id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="register-form" onsubmit="return validateForm(this)">
<div class="form-column">
<div class="form-group">
    <!-- Username Field -->
    <label for="username">Username*</label>
    <input type="text" id="username" name="username" placeholder="e.g. John123" required>
</div>
<!-- Password Field -->
<div class="form-group">
    <label for="password">Password*</label>
    <input type="password" id="password" name="password" placeholder="**********" required>
</div> 
<!-- Title Field -->
<div class="form-group">
    <label for="title">Title*</label>
    <input type="text" id="title" name="title" placeholder="e.g. Mr, Ms, Dr" required>
</div>
<!-- Full Name Field -->
<div class="form-group">
    <label for="fullname">Full Name*</label>
    <input type="text" id="fullname" name="fullname" placeholder="e.g. John Smith" required>
</div>
<!-- Gender Field -->
<div class="form-group">
    <label for="gender">Gender*</label>
    <select id="gender" name="gender" required>
        <option value="">Select: Male / Female / Other</option> 
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select>
</div>
<!-- Address Field -->
<div class="form-group">
    <label for="address">Address*</label>
    <input type="text" id="address" name="address" placeholder="e.g. 123 Caravan Lane, Leeds" required>
</div>
<!-- Telephone Field -->
<div class="form-group">
    <label for="telephone">Telephone*</label>
    <input type="text" id="telephone" name="telephone" placeholder="e.g. 07123456789" required>
</div>
<!-- Email Field -->
<div class="form-group">
            <label for="email">Email*</label>
            <input type="email" id="email" name="email" placeholder="e.g. john@email.com" required>
</div>
<!-- Profile Picture Field -->
<div class="form-group">
            <label for="profile">Profile Picture*</label>
            <input type="file" id="profile" name="profile">
</div>
<!-- Submit Button -->    
        
<button type="submit" class="register-btn">Register</button>

</div>
</form>
</div>
</div>
<script src="register.js"></script>
</body>
</html>