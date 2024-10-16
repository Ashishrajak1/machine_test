<?php
session_start();
$otpError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['otp']) {
        header("Location: http://localhost/machine_test/index.php"); // Redirect to product list
        exit();
    } else {
        $otpError = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link rel="stylesheet" href="http://localhost/machine_test/css/main.css">
</head>

<body class="auth_body">
    <div class="container">
        <div class="inner_container">
            <h2>OTP Verification</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="otp">Enter OTP</label>
                    <input type="text" name="otp" id="otp" required>
                    <span class="error-message"><?= $otpError ?></span>
                </div>
                <button type="submit">Verify OTP</button>
            </form>
        </div>
    </div>
</body>

</html>