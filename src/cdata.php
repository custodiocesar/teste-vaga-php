<?php
include_once __DIR__ . '/../database/database.php';

try {
    $pdo = conectarBanco();
    
    $sql = "
        SELECT c.id, c.nome, c.email, c.habilidades, 
            SUM(CASE WHEN i.vaga_id > 0 THEN 1 ELSE 0 END) AS vagas_inscritas
        FROM candidatos c
        LEFT JOIN inscricoes i ON c.id = i.candidato_id
        GROUP BY c.id, c.nome, c.email, c.habilidades;
    ";
    $stmt = $pdo->query($sql);
    $data1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $data1 = [];
}

$pdo = null;
?>
