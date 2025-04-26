<?php
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        
        // Get movie name for confirmation message
        $getNameStmt = $pdo->prepare("SELECT name FROM movies WHERE id = ?");
        $getNameStmt->execute([$_POST['id']]);
        $movie = $getNameStmt->fetch();
        
        if (!$movie) {
            http_response_code(404);
            echo json_encode(['error' => 'Movie not found']);
            exit;
        }
        
        // Delete the movie
        $stmt = $pdo->prepare("DELETE FROM movies WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(['message' => "Movie '{$movie['name']}' deleted successfully"]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Movie not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
