<?php
include 'config.php'; 

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $supplier = $_POST['supplier'];
    $current_stock = $_POST['current_stock'];
    $minimum_stock = $_POST['minimum_stock'];
    $maximum_stock = $_POST['maximum_stock'];
    $stock_unit = $_POST['stock_unit'];

    $query = "INSERT INTO products (product_name, supplier, current_stock, minimum_stock, maximum_stock, stock_unit) 
              VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiiis", $product_name, $supplier, $current_stock, $minimum_stock, $maximum_stock, $stock_unit);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: productlist.php");
    exit();
}
?>
