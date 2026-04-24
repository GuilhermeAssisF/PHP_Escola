<?php
// ===== Configuração do Banco de Dados (PDO SQLite) =====

function getConnection() {
    static $pdo = null;
    if ($pdo === null) {
        $dbPath = dirname(__DIR__, 2) . '/database.sqlite';
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec('PRAGMA foreign_keys = ON');
    }
    return $pdo;
}

function initDatabase() {
    $pdo = getConnection();

    // Criar tabelas
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        perfil TEXT CHECK(perfil IN ('admin','professor')) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS alunos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        matricula VARCHAR(45) NOT NULL UNIQUE,
        nome VARCHAR(70) NOT NULL,
        data_nascimento DATE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS turmas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome VARCHAR(45) NOT NULL,
        ano_letivo INTEGER NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS disciplinas (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome VARCHAR(45) NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS turma_aluno (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        turma_id INTEGER NOT NULL,
        aluno_id INTEGER NOT NULL,
        FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
        FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS alocacoes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        usuario_id INTEGER NOT NULL,
        turma_id INTEGER NOT NULL,
        disciplina_id INTEGER NOT NULL,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
        FOREIGN KEY (turma_id) REFERENCES turmas(id) ON DELETE CASCADE,
        FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id) ON DELETE CASCADE
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS avaliacoes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        aluno_id INTEGER NOT NULL,
        alocacao_id INTEGER NOT NULL,
        nota_1 REAL NOT NULL DEFAULT 0,
        nota_2 REAL NOT NULL DEFAULT 0,
        faltas INTEGER NOT NULL DEFAULT 0,
        FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
        FOREIGN KEY (alocacao_id) REFERENCES alocacoes(id) ON DELETE CASCADE
    )");

    // Migrar senhas em texto puro para bcrypt
    $usuarios = $pdo->query("SELECT id, senha FROM usuarios")->fetchAll();
    foreach ($usuarios as $u) {
        // Se a senha tem menos de 60 caracteres, não é bcrypt
        if (strlen($u['senha']) < 60) {
            $hash = password_hash($u['senha'], PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt->execute([$hash, $u['id']]);
        }
    }

    // Criar admin padrão se não existir nenhum usuário
    $count = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    if ($count == 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Administrador', 'admin@escola.com', $hash, 'admin']);
    }

    return $pdo;
}

// Inicializa o banco automaticamente
$pdo = initDatabase();
?>
