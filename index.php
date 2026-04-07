<?php
require_once 'database.php';

// Contador para o dashboard
$countUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$countAlunos = $pdo->query("SELECT COUNT(*) FROM alunos")->fetchColumn();
$countTurmas = $pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
$countDisciplinas = $pdo->query("SELECT COUNT(*) FROM disciplinas")->fetchColumn();
$countAlocacoes = $pdo->query("SELECT COUNT(*) FROM alocacoes")->fetchColumn();
$countAvaliacoes = $pdo->query("SELECT COUNT(*) FROM avaliacoes")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Escolar — Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>📊 Dashboard</h1>
        <p>Visão geral do sistema de gerenciamento escolar</p>
    </div>

    <div class="dashboard-grid">
        <a href="usuarios.php" class="dash-card">
            <span class="card-icon">👤</span>
            <span class="card-count"><?= $countUsuarios ?></span>
            <span class="card-label">Usuários</span>
        </a>
        <a href="alunos.php" class="dash-card">
            <span class="card-icon">🎓</span>
            <span class="card-count"><?= $countAlunos ?></span>
            <span class="card-label">Alunos</span>
        </a>
        <a href="turmas.php" class="dash-card">
            <span class="card-icon">🏫</span>
            <span class="card-count"><?= $countTurmas ?></span>
            <span class="card-label">Turmas</span>
        </a>
        <a href="disciplinas.php" class="dash-card">
            <span class="card-icon">📚</span>
            <span class="card-count"><?= $countDisciplinas ?></span>
            <span class="card-label">Disciplinas</span>
        </a>
        <a href="alocacoes.php" class="dash-card">
            <span class="card-icon">📋</span>
            <span class="card-count"><?= $countAlocacoes ?></span>
            <span class="card-label">Alocações</span>
        </a>
        <a href="avaliacoes.php" class="dash-card">
            <span class="card-icon">📝</span>
            <span class="card-count"><?= $countAvaliacoes ?></span>
            <span class="card-label">Avaliações</span>
        </a>
    </div>
</div>

</body>
</html>
