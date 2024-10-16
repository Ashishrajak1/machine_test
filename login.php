<?php
session_start();
include('connection.php'); 

$emailError = $passwordError = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

  
    if (empty($email)) {
        $emailError = "Email is required.";
    }
    if (empty($password)) {
        $passwordError = "Password is required.";
    }


    if (empty($emailError) && empty($passwordError)) {
        $email = mysqli_real_escape_string($conn, $email);

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
            
                if (password_verify($password, $row['password'])) {
                    // Generate OTP and send email
                    $otp = rand(1000, 9999);
                    $_SESSION['otp'] = $otp;
                    $_SESSION['email'] = $email;
                    $_SESSION['username'] = $row['name'];
                    $_SESSION['userid'] = $row['id'];
                    $_SESSION['user_image'] = $row['profile_photo'];

                    $subject = 'Your OTP Code';
                    $message = "Your OTP code is: $otp";
                    $headers = "From: ashishrajak5005@gmail.com\r\n"; // sender email address

                     if (mail($email, $subject, $message, $headers)) {
                        header("Location: http://localhost/machine_test/otp_verify.php");
                        //header("Location: index.php");
                        exit();
                     } else {
                         echo "Failed to send OTP. Please try again.";
                        }
                } else {
                    $passwordError = "Invalid email or password.";
                }
            } else {
                $passwordError = "Invalid email or password.";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
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
    <title>Login Form</title>
    <link rel="stylesheet" href="http://localhost/machine_test/css/main.css">
</head>

<body class="auth_body">
    <div class="container">
        <div class="inner_container">
            <h2>Login Form</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email">
                    <span class="error-message"><?= $emailError ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                    <span class="error-message"><?= $passwordError ?></span>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="registration-prompt">
                <p>Don't have an account? <a href="http://localhost/machine_test/registration.php">Register here</a></p>
            </div>
        </div>
    </div>
</body>

</html>