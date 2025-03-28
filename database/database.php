<?php
function conectarBanco() {
    $databasePath = __DIR__ . '/database.sqlite';

    if (!file_exists($databasePath)) {
        throw new Exception("Arquivo de banco de dados não encontrado: $databasePath");
    }
    $pdo = new PDO("sqlite:" . $databasePath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

try {
    $pdo = conectarBanco();

// Criação das tabelas se não existirem
$pdo->exec("
    CREATE TABLE IF NOT EXISTS vagas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT NOT NULL,
        descricao TEXT NOT NULL,
        tipo TEXT CHECK(tipo IN ('CLT', 'PJ', 'Freelancer')) NOT NULL,
        status TEXT CHECK(status IN ('ativa', 'pausada')) DEFAULT 'ativa',
        data_criacao TEXT DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS candidatos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        habilidades TEXT
    );

    CREATE TABLE IF NOT EXISTS inscricoes (  -- 🔹 Corrigido nome da tabela
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        data_criacao TEXT DEFAULT CURRENT_TIMESTAMP,
        vaga_id INTEGER,
        candidato_id INTEGER,
        FOREIGN KEY (vaga_id) REFERENCES vagas(id) ON DELETE SET NULL,
        FOREIGN KEY (candidato_id) REFERENCES candidatos(id) ON DELETE SET NULL,
        UNIQUE (vaga_id, candidato_id)  -- 🔹 Evita inscrições duplicadas
    );
");
    
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
