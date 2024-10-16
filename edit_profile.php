<?php
include('connection.php');
$userId = $_GET['id'];
$name = $email = $phone = $address = $gender = $language = $profileDescription = $profilePhoto = "";
$nameErr = $emailErr = $phoneErr = $addressErr = $genderErr = $languageErr = $profileDescriptionErr = "";

$sql = "SELECT * FROM users WHERE id = $userId";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if ($user) {
    $name = $user['name'];
    $email = $user['email'];
    $phone = $user['phone'];
    $address = $user['address'];
    $gender = $user['gender'];
    $language = $user['language'];
    $profileDescription = $user['profile_description'];
    $profilePhoto = $user['profile_photo'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $profileDescription = $_POST['profile_description'];

    if (empty($name)) {
        $nameErr = "Name is required";
    }
    if (empty($email)) {
        $emailErr = "Email is required";
    }
    if (empty($phone)) {
        $phoneErr = "Phone number is required";
    }
    if (empty($address)) {
        $addressErr = "Address is required";
    }
    if (empty($gender)) {
        $genderErr = "Gender is required";
    }

    if (empty($profileDescription)) {
        $profileDescriptionErr = "Profile description is required";
    }


    if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($addressErr) && empty($genderErr) && empty($languageErr) && empty($profileDescriptionErr)) {
        $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address', gender='$gender', language='$language', profile_description='$profileDescription' WHERE id = $userId";

        if (mysqli_query($conn, $sql)) {

            header("Location: http://localhost/machine_test/index.php"); 
            exit();
        } else {
            echo "Error updating profile: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="http://localhost/machine_test/css/main.css">
</head>

<body>
    <?php include('header.php'); ?>
    <div class="form-container">
        <h1>Edit Profile</h1>
        <form action="" method="POST" class="profile-form">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?= $name; ?>">
            <div class="error"><?= $nameErr; ?></div>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?= $email; ?>">
            <div class="error"><?= $emailErr; ?></div>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?= $phone; ?>">
            <div class="error"><?= $phoneErr; ?></div>

            <label for="address">Address:</label>
            <textarea name="address"><?= $address; ?></textarea>
            <div class="error"><?= $addressErr; ?></div>

            <label for="gender">Gender:</label>
            <select name="gender" class="styled-select">
                <option value="male" <?php if($gender == 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if($gender == 'female') echo 'selected'; ?>>Female</option>
            </select>
            <div class="error"><?= $genderErr; ?></div>

            <label for="profile_description">Profile Description:</label>
            <textarea name="profile_description"><?= $profileDescription; ?></textarea>
            <div class="error"><?= $profileDescriptionErr; ?></div>

            <input type="submit" value="Update Profile" class="submit-btn">
        </form>
        <a href="http://localhost/machine_test/index.php" class="back-link">Back to Product List Page</a>
    </div>
</body>

</html>