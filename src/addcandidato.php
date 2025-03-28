<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('sqlite:' . realpath(__DIR__ . '/../database/database.sqlite'));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['nome']) || empty($data['email']) || empty($data['habilidades']) || empty($data['vaga_id'])) {
        throw new Exception('Todos os campos são obrigatórios.');
        var_dump($data);
    }

    $stmtCheck = $pdo->prepare("SELECT id FROM candidatos WHERE email = :email");
    $stmtCheck->execute([':email' => $data['email']]);
    $candidatoExistente = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($candidatoExistente) {
        
        $candidato_id = $candidatoExistente['id'];
    } else {
        
        $stmtCandidato = $pdo->prepare("INSERT INTO candidatos (nome, email, habilidades) VALUES (:nome, :email, :habilidades)");
        $stmtCandidato->execute([
            ':nome' => htmlspecialchars($data['nome']),
            ':email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL),
            ':habilidades' => $data['habilidades'] ?? null
        ]);
        $candidato_id = $pdo->lastInsertId();
    }

    $stmtInscricao = $pdo->prepare("INSERT INTO inscricoes (vaga_id, candidato_id) VALUES (:vaga_id, :candidato_id)");
    
    foreach ($data['vaga_id'] as $vaga_id) {
        
        $stmtCheckInscricao = $pdo->prepare("SELECT COUNT(*) FROM inscricoes WHERE vaga_id = :vaga_id AND candidato_id = :candidato_id");
        $stmtCheckInscricao->execute([
            ':vaga_id' => $vaga_id,
            ':candidato_id' => $candidato_id
        ]);
        $inscricaoExiste = $stmtCheckInscricao->fetchColumn();

        if ($inscricaoExiste == 0) {
            $stmtInscricao->execute([
                ':vaga_id' => $vaga_id,
                ':candidato_id' => $candidato_id
            ]);
        }
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>