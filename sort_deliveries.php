<?php
include 'config.php';

$sort_by = $_GET['sort_by'] ?? '';
$sort_order = $_GET['sort_order'] ?? 'ASC';
$valid_columns = ['product_id', 'supplier_name', 'quantity', 'order_date', 'arrive_date', 'total_price', 'status'];

if (!in_array($sort_by, $valid_columns) || !in_array($sort_order, ['ASC', 'DESC'])) {
    echo json_encode(["error" => "Invalid sort attribute or order"]);
    exit;
}

$sql = "SELECT * FROM deliveries ORDER BY $sort_by $sort_order";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
