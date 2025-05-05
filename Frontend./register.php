<?php
// PHP code for processing form submission would go here
// This would handle validation, database insertion, etc.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration - RentMyCaravan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
    <a href="index.php">Home</a>
    <a href="register.php" class="active">Register</a>
    <a href="login.php">Login</a>
    <a href="add_caravan.php">Upload</a>
    <a href="about.php">About us</a>
    <a href="contact.php">Contact us</a>
</nav>
<div class="container 960">
    <div class="register container">
        <h1>RentMyCaravan</h1>
<p class="intruction"> Complete this form and select register button to complete.</p>
<form id="registerForm" method="POST" class="register-form" onsubmit="return validateForm(this)">
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
    <label for="Telephone">Telephone*</label>
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
    </form>
</div>
</body>
</html>