<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .vh-100 {
            height: 100vh;
        }
        .nav-link {
            display: block;
            padding: 10px;
            text-decoration: none;
        }
        .nav-link:hover {
            background-color: #495057;
            border-radius: 4px;
        }
        .bg-custom {
            background-color: #a6895b;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-custom text-light p-3 vh-100" style="width: 200px;">
    <h4 class="text-center">Menu</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a href="dashboard.php" class="nav-link text-light">
                <i class="fas fa-home me-2"></i>Home
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="stockin.php" class="nav-link text-light">
                <i class="fas fa-box me-2"></i>Stock In
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="stock_out.php" class="nav-link text-light">
                <i class="fas fa-truck me-2"></i>Stock Out
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="productlist.php" class="nav-link text-light">
                <i class="fas fa-chart-bar me-2"></i>Product List
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="generate_report.php" class="nav-link text-light">
                <i class="fas fa-file-alt me-2"></i>Generate Report
            </a>
        </li>
    </ul>
</nav>
    <div class="container mt-3">
        <h2 class="text-center">Add Product</h2>
        <?php
        session_start();
        if (isset($_SESSION['success'])) {
            echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="add_product.php" method="POST" class="shadow p-4 bg-light rounded">
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter product name" required>
            </div>
            <div class="mb-3">
                <label for="supplier" class="form-label">Supplier</label>
                <input type="text" name="supplier" id="supplier" class="form-control" placeholder="Enter supplier name" required>
            </div>
            <div class="mb-3">
                <label for="current_stock" class="form-label">Current Stock</label>
                <input type="number" name="current_stock" id="current_stock" class="form-control" placeholder="Enter current stock" required>
            </div>
            <div class="mb-3">
                <label for="minimum_stock" class="form-label">Minimum Stock</label>
                <input type="number" name="minimum_stock" id="minimum_stock" class="form-control" placeholder="Enter minimum stock" required>
            </div>
            <div class="mb-3">
                <label for="maximum_stock" class="form-label">Maximum Stock</label>
                <input type="number" name="maximum_stock" id="maximum_stock" class="form-control" placeholder="Enter maximum stock" required>
            </div>
            <div class="mb-3">
                <label for="stock_unit" class="form-label">Stock Unit</label>
                <select name="stock_unit" id="stock_unit" class="form-control" required>
                    <option value="units">Units</option>
                    <option value="pieces">Pieces</option>
                    <option value="cans">Cans</option>
                    <option value="bottles">Bottles</option>
                    <option value="boxes">Boxes</option>
                    <option value="packs">Packs</option>
                    <option value="kg">Kg</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Product</button>
        </form>
    </div>
</div>
</body>
</html>
