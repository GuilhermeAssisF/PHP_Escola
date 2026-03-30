<?php
// Detecta a página atual para marcar o item ativo na sidebar
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="sidebar">
    <div class="sidebar-brand">
        <h2><span class="icon">🎓</span> <span>Sistema Escolar</span></h2>
        <p>Gerenciamento Acadêmico</p>
    </div>
    <ul class="nav-list">
        <li>
            <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">
                <span class="nav-icon">📊</span> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="usuarios.php" class="<?= $currentPage === 'usuarios.php' ? 'active' : '' ?>">
                <span class="nav-icon">👤</span> <span>Usuários</span>
            </a>
        </li>
        <li>
            <a href="alunos.php" class="<?= $currentPage === 'alunos.php' ? 'active' : '' ?>">
                <span class="nav-icon">🎓</span> <span>Alunos</span>
            </a>
        </li>
        <li>
            <a href="turmas.php" class="<?= $currentPage === 'turmas.php' ? 'active' : '' ?>">
                <span class="nav-icon">🏫</span> <span>Turmas</span>
            </a>
        </li>
        <li>
            <a href="disciplinas.php" class="<?= $currentPage === 'disciplinas.php' ? 'active' : '' ?>">
                <span class="nav-icon">📚</span> <span>Disciplinas</span>
            </a>
        </li>
        <li>
            <a href="turma_aluno.php" class="<?= $currentPage === 'turma_aluno.php' ? 'active' : '' ?>">
                <span class="nav-icon">🔗</span> <span>Turma-Aluno</span>
            </a>
        </li>
        <li>
            <a href="alocacoes.php" class="<?= $currentPage === 'alocacoes.php' ? 'active' : '' ?>">
                <span class="nav-icon">📋</span> <span>Alocações</span>
            </a>
        </li>
        <li>
            <a href="avaliacoes.php" class="<?= $currentPage === 'avaliacoes.php' ? 'active' : '' ?>">
                <span class="nav-icon">📝</span> <span>Avaliações</span>
            </a>
        </li>
    </ul>
</nav>
