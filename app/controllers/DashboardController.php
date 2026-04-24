<?php
// Controller: Dashboard
require_once __DIR__ . '/../models/Aluno.php';
require_once __DIR__ . '/../models/Turma.php';
require_once __DIR__ . '/../models/Disciplina.php';
require_once __DIR__ . '/../models/Alocacao.php';
require_once __DIR__ . '/../models/Avaliacao.php';

class DashboardController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function index() {
        $countUsuarios = $this->pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        $countAlunos = (new Aluno($this->pdo))->count();
        $countTurmas = (new Turma($this->pdo))->count();
        $countDisciplinas = (new Disciplina($this->pdo))->count();
        $countAlocacoes = (new Alocacao($this->pdo))->count();
        $countAvaliacoes = (new Avaliacao($this->pdo))->count();

        $userPerfil = $_SESSION['user_perfil'];

        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/sidebar.php';
        include __DIR__ . '/../views/dashboard/index.php';
        include __DIR__ . '/../views/layout/footer.php';
    }
}
?>
