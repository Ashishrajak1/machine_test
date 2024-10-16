<?php
include('connection.php');

$id = $_GET['id'];

$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="stylesheet" href="http://localhost/machine_test/css/main.css">
</head>

<body>
    <?php include('header.php'); ?>
    <div class="product-detail-container">
        <h1 class="page-title">Product Detail</h1>
        <div class="product-detail-card">
            <img class="product-image-large"
                src="http://localhost/machine_test/uploads/<?php echo $product['image']; ?>" alt="Product Image"
                width="300">
            <div class="product-info">
                <p><strong>Name:</strong> <?php echo $product['name']; ?></p>
                <p><strong>Description:</strong> <?php echo $product['description']; ?></p>
                <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                <p><strong>Quantity:</strong> <?php echo $product['quantity']; ?></p>
                <p><strong>Created At:</strong> <?php echo date('d-m-Y', strtotime($product['created_at'])); ?></p>
                <div class="product-actions">
                    <a class="action-link edit"
                        href="http://localhost/machine_test/product_add_edit.php?id=<?php echo $product['id']; ?>">Edit</a>
                    <a class="action-link back" href="http://localhost/machine_test/index.php">Back to Product List
                        Page</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
mysqli_close($conn);
?>