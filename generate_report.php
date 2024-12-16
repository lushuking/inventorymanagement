<?php 
$conn = new mysqli('localhost', 'root', '', 'snack inn');

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$sql = "SELECT product_id, product_name, supplier, current_stock, stock_unit, status FROM products ORDER BY product_name ASC";
$result = $conn->query($sql);

$html = "
<!DOCTYPE html>
<html>
<head>
    <title>Inventory Overview Report</title>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css' rel='stylesheet'> <!-- Font Awesome -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .back-button {
            margin-bottom: 20px;
        }
        .back-button button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .back-button button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .low-stock {
            background-color: #ffcccc; /* Highlight low stock items */
        }
        .button-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .button-container button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='back-button'>
            <button onclick='window.location.href=\"dashboard.php\"'>
                <i class='fas fa-arrow-left'></i> Back to Dashboard
            </button>
        </div>
        <h2>Inventory Overview Report</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Supplier</th>
                <th>Current Stock</th>
                <th>Stock Unit</th>
                <th>Status</th>
            </tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lowStockClass = $row['current_stock'] < $row['status'] ? 'low-stock' : '';
        $html .= "
        <tr class='{$lowStockClass}'>
            <td>{$row['product_id']}</td>
            <td>{$row['product_name']}</td>
            <td>{$row['supplier']}</td>
            <td>{$row['current_stock']}</td>
            <td>{$row['stock_unit']}</td>
            <td>{$row['status']}</td>
        </tr>";
    }
} else {
    $html .= "<tr><td colspan='6'>No data found</td></tr>";
}

$html .= "
        </table>
        <div class='button-container'>
            <button onclick='window.print()'>
                <i class='fas fa-print'></i> Print Report
            </button>
        </div>
    </div>
</body>
</html>";

echo $html;

$conn->close();
?>
