<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    
    $db = new PDO('sqlite:' . realpath(__DIR__ . '/../database/database.sqlite'));
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['titulo'])) {
        throw new Exception('Título inválido (máx 100 caracteres)');
    }

    $stmt = $db->prepare('INSERT INTO vagas 
        (titulo, descricao, tipo, status, data_criacao) 
        VALUES (:titulo, :descricao, :tipo, :status, CURRENT_TIMESTAMP)');

    $success = $stmt->execute([
        ':titulo' => htmlspecialchars($data['titulo']),
        ':descricao' => htmlspecialchars($data['descricao']),
        ':tipo' => $data['tipo'],
        ':status' => $data['status'] ?? 'ativo'
    ]);

    if (!$success) {
        throw new Exception('Falha na execução da query');
    }

    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);

} catch (PDOException $e) {
    error_log($e->getMessage()); // Log no servidor
    echo json_encode(['error' => 'Erro no banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log($e->getMessage()); // Log no servidor
    echo json_encode(['error' => $e->getMessage()]);
}