<?php
header('Content-Type: application/json');

include_once __DIR__ . '/../database/database.php';

try {
    $pdo = conectarBanco();
    
    $id = $_POST['id'] ?? null;

    if (!$id) {
        throw new Exception("ID não fornecido");
    }

    $stmt = $pdo->prepare('DELETE FROM candidatos WHERE id = :id');
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Falha ao excluir candidato']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
exit;