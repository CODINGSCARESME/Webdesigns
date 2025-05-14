<?php
// Step 1: Connect to MySQL database
$host = 'localhost';
$db = 'rentmycar';  // Make sure this matches the name you used in phpMyAdmin
$user = 'root';
$pass = ''; // Leave empty unless you set a MySQL password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Clean input function (prevents XSS)
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Step 3: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and clean inputs
    $username = clean_input($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password storage
    $title = clean_input($_POST['title']);
    $fullname = clean_input($_POST['fullname']);
    $gender = clean_input($_POST['gender']);
    $address = clean_input($_POST['address']);
    $telephone = clean_input($_POST['telephone']);
    $email = clean_input($_POST['email']);

    // Step 4: Handle profile picture upload
    $targetDir = "uploads/";
    $profileName = basename($_FILES["profile"]["name"]);
    $targetFile = $targetDir . uniqid() . "_" . $profileName;
    $uploadOk = 1;

    // Validate it's an image
    $check = getimagesize($_FILES["profile"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File is not an image.');</script>";
        $uploadOk = 0;
    }

    // Upload image and insert user if no issues
    if ($uploadOk && move_uploaded_file($_FILES["profile"]["tmp_name"], $targetFile)) {
        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO users (username, password, title, fullname, gender, address, telephone, email, profile_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $username, $password, $title, $fullname, $gender, $address, $telephone, $email, $targetFile);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Failed to upload image.');</script>";
    }

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

    <nav>
    <a href="index.php">Home</a>
    <a href="register.php" class="active">Register</a>
    <a href="login.php">Login</a>
    <a href="add_caravan.php">Upload</a>
    <a href="about.php">About us</a>
    <a href="contact.php">Contact us</a>
</nav>

<div class="register-form">
        <h2>Account Registration Form</h2>
        <p class="intruction">Complete this form and select register button to complete.</p>

        <form id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="register-form" onsubmit="return validateForm(this)">
<div class="form-column">
<div class="form-group">
    <label for="username">Username*</label>
    <input type="text" id="username" name="username" placeholder="e.g. John123" required>
</div>
<div class="form-group">
    <label for="password">Password*</label>
    <input type="password" id="password" name="password" placeholder="**********" required>
</div> 
<div class="form-group">
    <label for="title">Title*</label>
    <input type="text" id="title" name="title" placeholder="e.g. Mr, Ms, Dr" required>
</div>
<div class="form-group">
    <label for="fullname">Full Name*</label>
    <input type="text" id="fullname" name="fullname" placeholder="e.g. John Smith" required>
</div>
<div class="form-group">
    <label for="gender">Gender*</label>
    <select id="gender" name="gender" required>
        <option value="">Select: Male / Female / Other</option> 
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
    </select>
</div>
<div class="form-group">
    <label for="address">Address*</label>
    <input type="text" id="address" name="address" placeholder="e.g. 123 Caravan Lane, Leeds" required>
</div>
<div class="form-group">
    <label for="telephone">Telephone*</label>
    <input type="text" id="telephone" name="telephone" placeholder="e.g. 07123456789" required>
</div>
<div class="form-group">
            <label for="email">Email*</label>
            <input type="email" id="email" name="email" placeholder="e.g. john@email.com" required>
</div>
<div class="form-group">
            <label for="profile">Profile Picture*</label>
            <input type="file" id="profile" name="profile" required>
</div>
        

    <button type="submit" class="register-btn">Register</button>
</div>
</form>
</div>
</div>
<script src="register.js"></script>
</body>
</html>