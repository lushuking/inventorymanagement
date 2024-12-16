<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snack Inn Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            display: flex;
            flex-wrap: nowrap;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #a6895b;
            border-right: 1px solid #ddd;
            color: white;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar .nav-link {
            color: white;
            font-weight: bold;
            margin: 5px 10px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            transition: background 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .menu-icon {
            display: none;
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
            font-size: 24px;
            cursor: pointer;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                height: 100%;
                z-index: 999;
            }

            .menu-icon {
                display: block;
            }

            .content {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <?php session_start(); ?>

    <div class="menu-icon" id="menuIcon">
        <i class="fas fa-bars"></i>
    </div>

    <nav class="sidebar" id="sidebar">
        <div class="p-3">
            <h4><i class="fas fa-store"></i> Snack Inn</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="stockin.php">
                    <i class="fas fa-box"></i> Stock In
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="stock_out.php">
                    <i class="fas fa-truck"></i> Stock Out
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="productlist.php">
                    <i class="fas fa-list"></i> Product List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="generate_report.php">
                    <i class="fas fa-file-alt"></i> Generate Report
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center text-dark mb-4">Welcome to Snack Inn Inventory Dashboard</h1>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fas fa-box fs-1"></i>
                        <h3 class="card-title mt-3">Stock In</h3>
                        <p class="card-text">Add or update stock items in the inventory.</p>
                        <a href="stockin.php" class="btn btn-light mt-3">View Stock</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fas fa-truck fs-1"></i>
                        <h3 class="card-title mt-3">Stock Out</h3>
                        <p class="card-text">Check the current stock levels and details.</p>
                        <a href="stock_out.php" class="btn btn-light mt-3">View Stock</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fas fa-list fs-1"></i>
                        <h3 class="card-title mt-3">Product List</h3>
                        <p class="card-text">Check the products in the inventory.</p>
                        <a href="productlist.php" class="btn btn-light mt-3">View Product</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white text-center shadow-sm border-0">
                    <div class="card-body">
                        <i class="fas fa-file-alt fs-1"></i>
                        <h3 class="card-title mt-3">Generate Report</h3>
                        <p class="card-text">Create detailed inventory reports.</p>
                        <a href="generate_report.php" class="btn btn-light mt-3">View Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuIcon = document.getElementById('menuIcon');
        const sidebar = document.getElementById('sidebar');

        menuIcon.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>
</html>
