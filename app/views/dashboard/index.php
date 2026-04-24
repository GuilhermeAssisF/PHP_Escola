<?php $pageTitle = 'Dashboard — Sistema Escolar'; ?>
<div class="main-content">
    <div class="page-header">
        <h1>📊 Dashboard</h1>
        <p>Visão geral do sistema de gerenciamento escolar</p>
    </div>

    <div class="dashboard-grid">
        <?php if ($userPerfil === 'admin'): ?>
        <a href="index.php?page=usuarios" class="dash-card">
            <span class="card-icon">👤</span>
            <span class="card-count"><?= $countUsuarios ?></span>
            <span class="card-label">Usuários</span>
        </a>
        <a href="index.php?page=alunos" class="dash-card">
            <span class="card-icon">🎓</span>
            <span class="card-count"><?= $countAlunos ?></span>
            <span class="card-label">Alunos</span>
        </a>
        <a href="index.php?page=turmas" class="dash-card">
            <span class="card-icon">🏫</span>
            <span class="card-count"><?= $countTurmas ?></span>
            <span class="card-label">Turmas</span>
        </a>
        <a href="index.php?page=disciplinas" class="dash-card">
            <span class="card-icon">📚</span>
            <span class="card-count"><?= $countDisciplinas ?></span>
            <span class="card-label">Disciplinas</span>
        </a>
        <?php endif; ?>
        <a href="index.php?page=alocacoes" class="dash-card">
            <span class="card-icon">📋</span>
            <span class="card-count"><?= $countAlocacoes ?></span>
            <span class="card-label">Alocações</span>
        </a>
        <?php if ($userPerfil === 'professor'): ?>
        <a href="index.php?page=avaliacoes" class="dash-card">
            <span class="card-icon">📝</span>
            <span class="card-count"><?= $countAvaliacoes ?></span>
            <span class="card-label">Avaliações</span>
        </a>
        <?php endif; ?>
    </div>
</div>
