<?php
require_once __DIR__ . '/../../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$categoryName = $data['categoryName'] ?? '';
$productName = $data['productName'] ?? '';
$sizes = ['XS', 'S', 'M', 'L', 'XL']; // Default sizes

if (!empty($categoryName) && !empty($productName)) {
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        
        // Begin transaction
        $pdo->beginTransaction();
        
        // Insert a record for each size
        $stmt = $pdo->prepare("INSERT INTO inventorybaju (category, product, size, quantity) VALUES (?, ?, ?, 0)");
        
        foreach ($sizes as $size) {
            $stmt->execute([$categoryName, $productName, $size]);
        }
        
        $pdo->commit();
        
        http_response_code(200);
        echo json_encode(['message' => 'Category and product added successfully']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Category and product names are required']);
}
