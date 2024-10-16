<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$username = $_SESSION['username'];
$userImage = $_SESSION['user_image'];
$id = $_SESSION['userid']
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/machine_test/css/main.css">
</head>

<body>
    <header class="site-header">
        <div class="header-content">
            <img src="http://localhost/machine_test/<?php echo $userImage; ?>" alt="User Image" class="user-image">
            <span class="username"><?php echo htmlspecialchars($username); ?></span>
            <a href="http://localhost/machine_test/edit_profile.php?id=<?=$id?>" class="edit-profile-btn">Edit
                Profile</a>
        </div>
    </header>
</body>

</html>