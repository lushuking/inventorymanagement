<?php
include 'config.php';

$itemsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

$totalCountQuery = "SELECT COUNT(*) as total FROM products";
$totalCountResult = mysqli_query($conn, $totalCountQuery);
$totalCount = mysqli_fetch_assoc($totalCountResult)['total'];
$totalPages = ceil($totalCount / $itemsPerPage);

$query = "SELECT product_id, product_name, supplier, current_stock, status FROM products ORDER BY product_name ASC LIMIT $offset, $itemsPerPage";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching products: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Out</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        .vh-100 {
            height: 100vh;
        }
        .d-flex {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
        }
        .bg-custom {
            background-color: #a6895b;
        }
        .container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
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
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="d-flex">
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
        <h1 class="text-dark">Stock Out</h1>
        
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success"><?php echo $_GET['success']; ?></div>
        <?php } ?>
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
        <?php } ?>

        <table class="table table-bordered table-striped">
            <thead class="table-warning">
                <tr>
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Supplier</th>
                    <th>Current Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['supplier']); ?></td>
                        <td><?php echo htmlspecialchars($row['current_stock']); ?></td>
                        <td>
                            <?php
                            $statusClass = $row['status'] === 'Unavailable' ? 'text-danger' : ($row['status'] === 'Low' ? 'text-warning' : 'text-success');
                            echo "<span class='$statusClass'>" . htmlspecialchars($row['status']) . "</span>";
                            ?>
                        </td>
                        <td>
                            <?php if ($row['status'] !== 'Unavailable') { ?>
                                <form action="process_stockout.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['product_id']); ?>">
                                    <button type="submit" class="btn btn-danger">Stock Out</button>
                                </form>
                            <?php } else { ?>
                                <button class="btn btn-secondary" disabled>Unavailable</button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</div>

</body>
</html>
