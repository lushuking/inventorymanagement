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
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Out</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
       body {
    background-color: rgba(210, 180, 140, 0.3); 
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.card {
    background-color: rgba(245, 245, 220, 0.7); 
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
}

        .status-available {
            color: limegreen;
        }
        .card img {
            display: none; 
        }
    </style>
</head>
<body>
    <div class="card">
        <h3><?php echo htmlspecialchars($product_name); ?></h3>
        <p>Available stocks: <span class="status-available"><?php echo $current_stock; ?></span></p>
        <form action="confirm_stockout.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <button type="submit" class="btn btn-danger w-100 mb-2">- Stock Out</button>
        </form>
        <a href="stock_out.php" class="btn btn-secondary w-100">Cancel</a>
    </div>
</body>
</html>
