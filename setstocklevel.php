<?php
include 'config.php';  

if (isset($_GET['product_id'])) {
    $id = $_GET['product_id'];

    $sql = "SELECT * FROM products WHERE product_id = $id";
    $result = $conn->query($sql);
    if ($result) {
        $product = $result->fetch_assoc();
    } else {
        echo "Error fetching product: " . $conn->error;
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $current_stock = $_POST['current_stock'];
    $minimum_stock = $_POST['minimum_stock']; 

    if ($current_stock < $minimum_stock) {
        $status = 'Low'; 
    } else {
        $status = 'Normal'; 
    }


    $sql = "UPDATE products SET current_stock = $current_stock, status = '$status' WHERE product_id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: productlist.php"); 
        exit();
    } else {
        echo "Error updating product: " . $conn->error; 
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Set Stock Level</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .modal-content {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-save {
            background-color: #28a745;
            color: white;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
        .btn-save:hover, .btn-cancel:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5 d-flex justify-content-center">
        <div class="modal-content p-4">
            <h3 class="text-center mb-4">Set Stock Level for <?php echo htmlspecialchars($product['product_name']); ?></h3>
            <form method="POST" action="setstocklevel.php">
                <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">

                <div class="mb-3">
                    <label for="product_id" class="form-label">Product ID:</label>
                    <input type="text" class="form-control" id="product_id" value="<?php echo $product['product_id']; ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="current_stock" class="form-label">Current Stock:</label>
                    <input type="text" class="form-control" id="current_stock" name="current_stock" value="<?php echo $product['current_stock']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="minimum_stock" class="form-label">Minimum Stock Level:</label>
                    <input type="number" class="form-control" id="minimum_stock" name="minimum_stock" placeholder="Enter minimum stock level" value="<?php echo $product['minimum_stock']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="maximum_stock" class="form-label">Maximum Stock Level:</label>
                    <input type="number" class="form-control" id="maximum_stock" name="maximum_stock" placeholder="Enter maximum stock level" value="<?php echo $product['maximum_stock']; ?>">
                </div>

                <div class="mb-3">
                    <label for="supplier" class="form-label">Supplier:</label>
                    <input type="text" class="form-control" id="supplier" value="<?php echo htmlspecialchars($product['supplier']); ?>" readonly>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-save px-4">Save</button>
                    <a href="productlist.php" class="btn btn-cancel px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
