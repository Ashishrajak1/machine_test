<?php
include('connection.php');
$id = $_GET['id'];

$sql = "DELETE FROM products WHERE id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: http://localhost/machine_test/index.php");
    exit();
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
?>