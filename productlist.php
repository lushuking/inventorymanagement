<?php
include 'config.php'; 

$deleteMessage = ""; 

if (isset($_POST['deleteProduct'])) {
    $productId = $_POST['product_id'];
    $sqlDelete = "DELETE FROM products WHERE product_id='$productId'";
    if ($conn->query($sqlDelete)) {
        $deleteMessage = "Product successfully deleted!";
    } else {
        $deleteMessage = "Error deleting product: " . $conn->error;
    }
}
$sqlUpdateStatus = "
UPDATE products
SET status = CASE 
    WHEN current_stock < minimum_stock THEN 'Low'
    ELSE 'Normal'
END";
$conn->query($sqlUpdateStatus);

$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : 'product_name';
$orderDirection = isset($_GET['orderDirection']) && $_GET['orderDirection'] === 'DESC' ? 'DESC' : 'ASC';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$itemsPerPage = 10; 
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

$sqlCount = "SELECT COUNT(*) AS total FROM products WHERE product_name LIKE '%$searchTerm%' OR product_id LIKE '%$searchTerm%' OR supplier LIKE '%$searchTerm%' OR status LIKE '%$searchTerm%'";
$totalResult = $conn->query($sqlCount);
$totalRow = $totalResult->fetch_assoc();
$totalItems = $totalRow['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

$sql = "SELECT * FROM products WHERE product_name LIKE '%$searchTerm%' OR product_id LIKE '%$searchTerm%' OR supplier LIKE '%$searchTerm%' OR status LIKE '%$searchTerm%' ORDER BY $orderBy $orderDirection LIMIT $itemsPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
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
    <?php include 'navbar.php'; ?>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-dark">Product List</h1>
            <div>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="product_id" id="deleteProductId" value="">
                    <button type="submit" name="deleteProduct" id="deleteButton" class="btn btn-danger" disabled>Delete</button>
                </form>
                <button class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#groupByModal">Group</button>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#sortModal">Sort</button>
            </div>
        </div>

        <?php if ($deleteMessage) { ?>
            <div class="alert alert-info">
                <?php echo $deleteMessage; ?>
            </div>
        <?php } ?>

        <div class="mb-3 mt-1">
            <form class="d-flex" method="GET">
                <input class="form-control form-control-sm me-2" type="search" name="search" placeholder="Search..." aria-label="Search" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button class="btn btn-outline-primary btn-sm" type="submit">Search</button>
            </form>
        </div>
        
        <table class="table table-bordered table-striped">
            <thead class="table-warning">
                <tr>
                    <th>Select</th>
                    <th>Product ID</th>
                    <th>Products</th>
                    <th>Supplier</th>
                    <th>Current Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <input type="radio" name="selectedProduct" value="<?php echo $row['product_id']; ?>" onchange="enableDeleteButton('<?php echo $row['product_id']; ?>')">
                        </td>
                        <td><?php echo $row['product_id']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['supplier']; ?></td>
                        <td><?php echo $row['current_stock'] . ' ' . $row['stock_unit']; ?></td>
                        <td>
                            <?php 
                            if ($row['status'] == 'Low') {
                                echo "<span class='text-danger fw-bold'>" . $row['status'] . "</span>";
                            } else {
                                echo $row['status'];
                            }
                            ?>
                        </td>
                        <td>
                            <a href="setstocklevel.php?product_id=<?php echo $row['product_id']; ?>" class="btn btn-primary btn-sm">Set stock level</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <nav>
    <ul class="pagination justify-content-center">
        <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>&search=<?php echo urlencode($searchTerm); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>&search=<?php echo urlencode($searchTerm); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
    </div>

    <div class="modal fade" id="sortModal" tabindex="-1" aria-labelledby="sortModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sortModalLabel">Sort by</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="sortForm" method="GET">
                        <div class="mb-3">
                            <label for="sortBySelect" class="form-label">Sort by</label>
                            <select class="form-select" id="sortBySelect" name="orderBy">
                                <option value="product_id">Product ID</option>
                                <option value="product_name">Product Name</option>
                                <option value="supplier">Supplier</option>
                                <option value="current_stock">Current Stock</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Order</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="orderDirection" id="ascOrder" value="ASC" checked>
                                <label class="form-check-label" for="ascOrder">
                                    Ascending
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="orderDirection" id="descOrder" value="DESC">
                                <label class="form-check-label" for="descOrder">
                                    Descending
                                </label>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="groupByModal" tabindex="-1" aria-labelledby="groupByModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupByModalLabel">Group by</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="groupByForm" method="GET" action="productlist.php">
                    <div class="mb-3">
                        <label for="groupBySelect" class="form-label">Group by</label>
                        <select class="form-select" id="groupBySelect" name="groupBy">
                            <option value="status">Status</option>
                            <option value="supplier">Supplier</option>
                            <option value="current_stock">Current Stock</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function enableDeleteButton(productId) {
            const deleteButton = document.getElementById('deleteButton');
            const deleteProductId = document.getElementById('deleteProductId');
            deleteButton.disabled = false;
            deleteProductId.value = productId;
        }
    </script>
    </div>
</div>
</body>
</html>

