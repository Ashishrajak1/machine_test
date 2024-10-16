<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: http://localhost/machine_test/login.php");
}
include('connection.php'); 

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listing</title>
    <link rel="stylesheet" href="http://localhost/machine_test/css/main.css">
</head>

<body>
    <?php include('header.php'); ?>
    <div class="product_list_container">
        <h1>Product Listing</h1>
        <a href="product_add_edit.php" class="add-product-btn">Add Product</a>
        <table border="1">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><img src="uploads/<?php echo $row['image']; ?>" alt="Product Image" width="100"></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td>
                    <a class="action-link view"
                        href="http://localhost/machine_test/product_detail.php?id=<?php echo $row['id']; ?>">View</a>
                    <a class="action-link edit"
                        href="http://localhost/machine_test/product_add_edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a class="action-link delete"
                        href="http://localhost/machine_test/product_delete.php?id=<?php echo $row['id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>

</html>