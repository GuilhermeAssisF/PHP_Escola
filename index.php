<?php
// ===== Front Controller (Roteador MVC) =====
session_start();

<<<<<<< HEAD
// Carrega o banco de dados
require_once __DIR__ . '/app/config/database.php';

// Página solicitada
$page = $_GET['page'] ?? 'dashboard';

// ===== Rotas Públicas (sem autenticação) =====
if ($page === 'login') {
    require_once __DIR__ . '/app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->login();
    } else {
        $controller->loginForm();
    }
    exit;
}

if ($page === 'logout') {
    require_once __DIR__ . '/app/controllers/AuthController.php';
    $controller = new AuthController($pdo);
    $controller->logout();
    exit;
}

// ===== Verificar Autenticação =====
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

// ===== Mapa de Permissões por Perfil =====
$permissions = [
    'dashboard'   => ['admin', 'professor'],
    'usuarios'    => ['admin'],
    'alunos'      => ['admin'],
    'turmas'      => ['admin'],
    'disciplinas' => ['admin'],
    'turma_aluno' => ['admin'],
    'alocacoes'   => ['admin', 'professor'],
    'avaliacoes'  => ['professor'],
];

$userPerfil = $_SESSION['user_perfil'];

// Verificar se a página existe no mapa de permissões
if (!isset($permissions[$page])) {
    $page = 'dashboard';
}

// Verificar permissão de acesso
if (!in_array($userPerfil, $permissions[$page])) {
    // Acesso negado — exibir página 403
    $pageTitle = 'Acesso Negado — Sistema Escolar';
    include __DIR__ . '/app/views/layout/header.php';
    include __DIR__ . '/app/views/layout/sidebar.php';
    echo '<div class="main-content">
        <div class="access-denied">
            <div class="denied-icon">🚫</div>
            <h1>Acesso Negado</h1>
            <p>Você não tem permissão para acessar esta página.</p>
            <a href="index.php" class="btn btn-primary">🏠 Voltar ao Dashboard</a>
        </div>
    </div>';
    include __DIR__ . '/app/views/layout/footer.php';
    exit;
}

// ===== Roteamento para Controllers =====
switch ($page) {
    case 'dashboard':
        require_once __DIR__ . '/app/controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->index();
        break;

    case 'usuarios':
        require_once __DIR__ . '/app/controllers/UsuarioController.php';
        $controller = new UsuarioController($pdo);
        $controller->index();
        break;

    case 'alunos':
        require_once __DIR__ . '/app/controllers/AlunoController.php';
        $controller = new AlunoController($pdo);
        $controller->index();
        break;

    case 'turmas':
        require_once __DIR__ . '/app/controllers/TurmaController.php';
        $controller = new TurmaController($pdo);
        $controller->index();
        break;

    case 'disciplinas':
        require_once __DIR__ . '/app/controllers/DisciplinaController.php';
        $controller = new DisciplinaController($pdo);
        $controller->index();
        break;

    case 'turma_aluno':
        require_once __DIR__ . '/app/controllers/TurmaAlunoController.php';
        $controller = new TurmaAlunoController($pdo);
        $controller->index();
        break;

    case 'alocacoes':
        require_once __DIR__ . '/app/controllers/AlocacaoController.php';
        $controller = new AlocacaoController($pdo);
        $controller->index();
        break;

    case 'avaliacoes':
        require_once __DIR__ . '/app/controllers/AvaliacaoController.php';
        $controller = new AvaliacaoController($pdo);
        $controller->index();
        break;

    default:
        require_once __DIR__ . '/app/controllers/DashboardController.php';
        $controller = new DashboardController($pdo);
        $controller->index();
        break;
}
=======
// Contador para o dashboard
$countUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$countAlunos = $pdo->query("SELECT COUNT(*) FROM alunos")->fetchColumn();
$countTurmas = $pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
$countDisciplinas = $pdo->query("SELECT COUNT(*) FROM disciplinas")->fetchColumn();
$countAlocacoes = $pdo->query("SELECT COUNT(*) FROM alocacoes")->fetchColumn();
$countAvaliacoes = $pdo->query("SELECT COUNT(*) FROM avaliacoes")->fetchColumn();
>>>>>>> 6d1e5012f0b181d53e3212eb1f43ac13bafd491f
?>
