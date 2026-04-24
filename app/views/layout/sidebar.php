<?php
$currentPage = $_GET['page'] ?? 'dashboard';
$perfil = $_SESSION['user_perfil'] ?? 'professor';
$userName = $_SESSION['user_nome'] ?? 'Usuário';
$userPerfil = $_SESSION['user_perfil'] ?? '';

// Definição dos menus e quais perfis podem acessar
$menuItems = [
    ['page' => 'dashboard',   'icon' => '📊', 'label' => 'Dashboard',   'perfis' => ['admin', 'professor']],
    ['page' => 'usuarios',    'icon' => '👤', 'label' => 'Usuários',    'perfis' => ['admin']],
    ['page' => 'alunos',      'icon' => '🎓', 'label' => 'Alunos',      'perfis' => ['admin']],
    ['page' => 'turmas',      'icon' => '🏫', 'label' => 'Turmas',      'perfis' => ['admin']],
    ['page' => 'disciplinas', 'icon' => '📚', 'label' => 'Disciplinas', 'perfis' => ['admin']],
    ['page' => 'turma_aluno', 'icon' => '🔗', 'label' => 'Turma-Aluno', 'perfis' => ['admin']],
    ['page' => 'alocacoes',   'icon' => '📋', 'label' => 'Alocações',   'perfis' => ['admin', 'professor']],
    ['page' => 'avaliacoes',  'icon' => '📝', 'label' => 'Avaliações',  'perfis' => ['professor']],
];
?>
<nav class="sidebar">
    <div class="sidebar-brand">
        <h2><span class="icon">🎓</span> <span>Sistema Escolar</span></h2>
        <p>Gerenciamento Acadêmico</p>
    </div>

    <!-- Info do Usuário Logado -->
    <div class="sidebar-user">
        <div class="user-avatar"><?= mb_substr($userName, 0, 1, 'UTF-8') ?></div>
        <div class="user-info">
            <span class="user-name"><?= htmlspecialchars($userName) ?></span>
            <span class="user-role"><span class="badge badge-<?= $userPerfil ?>"><?= $userPerfil ?></span></span>
        </div>
    </div>

    <ul class="nav-list">
        <?php foreach ($menuItems as $item): ?>
            <?php if (in_array($perfil, $item['perfis'])): ?>
            <li>
                <a href="index.php?page=<?= $item['page'] ?>" class="<?= $currentPage === $item['page'] ? 'active' : '' ?>">
                    <span class="nav-icon"><?= $item['icon'] ?></span> <span><?= $item['label'] ?></span>
                </a>
            </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <div class="sidebar-footer">
        <form method="POST" action="index.php?page=logout" id="logoutForm">
            <button type="submit" class="btn-logout">
                <span class="nav-icon">🚪</span> <span>Sair</span>
            </button>
        </form>
    </div>
</nav>
