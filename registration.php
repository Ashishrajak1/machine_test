<?php
session_start();
include('connection.php');

$nameError = $emailError = $phoneError = $profileDescriptionError = $photoError = $passwordError = $confirmPasswordError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $gender = $_POST['gender'] ?? '';
    $language = isset($_POST['language']) ? implode(", ", $_POST['language']) : '';
    $profile_description = trim($_POST['profile_description']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    
    if (empty($name)) {
        $nameError = "Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Please enter a valid email address.";
    }

    $email_check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($email_check->num_rows > 0) {
        $emailError = "Email is already in use.";
    }

    if (!preg_match('/^\d{10}$/', $phone)) {
        $phoneError = "Please enter a valid 10-digit phone number.";
    }

    if (strlen($profile_description) > 1500) {
        $profileDescriptionError = "Profile description must be less than 1500 characters.";
    }

    if (strlen($password) < 6) {
        $passwordError = "Password must be at least 6 characters long.";
    }

   if ($password !== $confirm_password) {
        $confirmPasswordError = "Passwords do not match.";
    }

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profilePhoto"]["name"]);
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileType, $allowed_types)) {
        $photoError = "Only JPG, JPEG, PNG & GIF files are allowed.";
    }

    if (empty($nameError) && empty($emailError) && empty($phoneError) && empty($passwordError) && empty($confirmPasswordError) && empty($photoError)) {
        
        if (move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $target_file)) {
        
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, phone, address, gender, language, profile_description, profile_photo, password) 
                    VALUES ('$name', '$email', '$phone', '$address', '$gender', '$language', '$profile_description', '$target_file', '$hashed_password')";
            
            if ($conn->query($sql) === TRUE) {
                header("Location: http://localhost/machine_test/login.php"); 
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $photoError = "Failed to upload photo.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="http://localhost/machine_test/css/main.css">
</head>

<body class="auth_body">

    <div class="container">
        <div class="innar_container">
            <h2>Registration Form</h2>
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name">
                    <span class="error-message"><?= $nameError ?></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email">
                    <span class="error-message"><?= $emailError ?></span>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" id="phone">
                    <span class="error-message"><?= $phoneError ?></span>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <input type="radio" name="gender" value="Male">
                    Male
                    <input type="radio" name="gender" value="Female">
                    Female
                </div>

                <div class="form-group">
                    <label>Language</label>
                    <input type="checkbox" name="language[]" value="Hindi"> Hindi
                    <input type="checkbox" name="language[]" value="English"> English
                    <input type="checkbox" name="language[]" value="German"> German
                </div>

                <div class="form-group">
                    <label for="profile_description">Profile Description</label>
                    <textarea name="profile_description" id="profile_description" rows="4"></textarea>
                    <span class="error-message"><?= $profileDescriptionError ?></span>
                </div>

                <div class="form-group">
                    <label for="profilePhoto">Profile Photo</label>
                    <input type="file" name="profilePhoto" id="profilePhoto" accept="image/*">
                    <span class="error-message"><?= $photoError ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                    <span class="error-message"><?= $passwordError ?></span>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password">
                    <span class="error-message"><?= $confirmPasswordError ?></span>
                </div>

                <button type="submit">Register</button>
            </form>
            <div class="login-prompt">
                <p>Already registered? <a href="http://localhost/machine_test/login.php">Login here</a></p>
            </div>
        </div>
    </div>

</body>

</html>