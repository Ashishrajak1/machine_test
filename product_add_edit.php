<?php
include('connection.php');

$name = $description = $price = $quantity = $image = "";
$nameErr = $descriptionErr = $priceErr = $quantityErr = $imageErr = "";
$isEditMode = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $isEditMode = true;

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $sql = "SELECT * FROM products WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        $name = $product['name'];
        $description = $product['description'];
        $price = $product['price'];
        $quantity = $product['quantity'];
        $image = $product['image'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['name'])) {
        $nameErr = "Product name is required";
    } else {
        $name = $_POST['name'];
    }

    if (empty($_POST['description'])) {
        $descriptionErr = "Description is required";
    } else {
        $description = $_POST['description'];
    }

    if (empty($_POST['price'])) {
        $priceErr = "Price is required";
    } elseif (!is_numeric($_POST['price']) || $_POST['price'] <= 0) {
        $priceErr = "Please enter a valid price";
    } else {
        $price = $_POST['price'];
    }

    if (empty($_POST['quantity'])) {
        $quantityErr = "Quantity is required";
    } elseif (!is_numeric($_POST['quantity']) || $_POST['quantity'] < 0) {
        $quantityErr = "Please enter a valid quantity";
    } else {
        $quantity = $_POST['quantity'];
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowedTypes = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        $fileSize = $_FILES['image']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            $imageErr = "Only JPG, PNG, and GIF images are allowed.";
        }

        if ($fileSize > 2 * 1024 * 1024) {
            $imageErr = "Image size should not exceed 2MB.";
        }

        if (empty($imageErr)) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $image = basename($_FILES['image']['name']);
            $targetFile = $targetDir . $image;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        }
    } else {
        if (!$isEditMode) {
            $imageErr = "Product image is required.";
        }
    }

    if (empty($nameErr) && empty($descriptionErr) && empty($priceErr) && empty($quantityErr) && empty($imageErr)) {
        if ($isEditMode) {
            if (!empty($image)) {
                $sql = "UPDATE products SET name='$name', description='$description', price='$price', quantity='$quantity', image='$image' WHERE id = $id";
            } else {
                $sql = "UPDATE products SET name='$name', description='$description', price='$price', quantity='$quantity' WHERE id = $id";
            }
        } else {
            $sql = "INSERT INTO products (name, description, price, quantity, image, created_at) VALUES ('$name', '$description', '$price', '$quantity', '$image', NOW())";
        }

        if (mysqli_query($conn, $sql)) {
            header("Location: http://localhost/machine_test/index.php");
            exit();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEditMode ? 'Edit Product' : 'Add Product'; ?></title>
    <link rel="stylesheet" href="http://localhost/machine_test/css/main.css">
</head>

<body>
    <?php include('header.php'); ?>
    <div class="form-container">
        <div class="inner_container">
            <h1><?php echo $isEditMode ? 'Edit Product' : 'Add Product'; ?></h1>
            <form action="" method="POST" enctype="multipart/form-data" class="product-form">
                <label for="name">Product Name:</label>
                <input type="text" name="name" value="<?php echo $name; ?>">
                <div class="error"><?php echo $nameErr; ?></div>

                <label for="description">Description:</label>
                <textarea name="description"><?php echo $description; ?></textarea>
                <div class="error"><?php echo $descriptionErr; ?></div>

                <label for="price">Price:</label>
                <input type="number" name="price" value="<?php echo $price; ?>">
                <div class="error"><?php echo $priceErr; ?></div>

                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" value="<?php echo $quantity; ?>">
                <div class="error"><?php echo $quantityErr; ?></div>

                <label for="image">Product Image:</label>
                <input type="file" name="image">
                <div class="error"><?php echo $imageErr; ?></div>

                <?php if ($isEditMode && $image): ?>
                <img class="product_img" src="http://localhost/machine_test/uploads/<?php echo $image; ?>"
                    alt="Product Image" width="100">
                <?php endif; ?>

                <input type="submit" value="<?php echo $isEditMode ? 'Update Product' : 'Add Product'; ?>"
                    class="submit-btn">
            </form>
            <a href="http://localhost/machine_test/index.php" class="back-link">Back to Product List Page</a>
        </div>
    </div>
</body>

</html>