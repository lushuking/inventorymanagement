<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $query = "SELECT product_name, current_stock FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
        $product_name = $product['product_name'];
        $current_stock = $product['current_stock'];

        $new_stock = $current_stock - 1;

        $updateQuery = "UPDATE products SET current_stock = ? WHERE product_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ii", $new_stock, $product_id);
        $updateStmt->execute();

        header("Location: stock_out.php?success=Stock+out+successfully");
        exit;
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
