<?php
include_once __DIR__ . '/../database/database.php';

try {
    $pdo = conectarBanco();

    $sql = "SELECT * FROM vagas";
    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as &$vaga) {
        $vaga['id'] = (string) $vaga['id'];
    }

} catch (PDOException $e) {
    $data = [];
}

$pdo = null;
?>