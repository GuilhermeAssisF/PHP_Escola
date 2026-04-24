<?php
// Controller: Avaliacao (apenas para professor)
require_once __DIR__ . '/../models/Avaliacao.php';
require_once __DIR__ . '/../models/Aluno.php';
require_once __DIR__ . '/../models/Alocacao.php';

class AvaliacaoController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new Avaliacao($pdo);
    }

    public function index() {
        $msg = '';
        $msgType = '';
        $editData = null;

        // ID do professor logado
        $usuarioId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            try {
                if ($action === 'create') {
                    $this->model->create($_POST);
                    $msg = 'Avaliação criada com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'update') {
                    $this->model->update($_POST['id'], $_POST);
                    $msg = 'Avaliação atualizada com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'delete') {
                    $this->model->delete($_POST['id']);
                    $msg = 'Avaliação excluída com sucesso!';
                    $msgType = 'success';
                }
            } catch (PDOException $e) {
                $msg = 'Erro: ' . $e->getMessage();
                $msgType = 'danger';
            }
        }

        if (isset($_GET['edit'])) {
            $editData = $this->model->getById($_GET['edit']);
        }

        $alocacaoModel = new Alocacao($this->pdo);

        // Filtra apenas avaliações do professor logado
        $registros = $alocacaoModel->getAvaliacoesByUsuario($usuarioId);

        // Filtra apenas alocações do professor logado (suas disciplinas/turmas)
        $alocacoes = $alocacaoModel->getForSelectByUsuario($usuarioId);

        // Filtra apenas alunos das turmas em que o professor leciona
        $alunos = $alocacaoModel->getAlunosByUsuario($usuarioId);

        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/sidebar.php';
        include __DIR__ . '/../views/avaliacoes/index.php';
        include __DIR__ . '/../views/layout/footer.php';
    }
}
?>
