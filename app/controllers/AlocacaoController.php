<?php
// Controller: Alocacao
require_once __DIR__ . '/../models/Alocacao.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Turma.php';
require_once __DIR__ . '/../models/Disciplina.php';

class AlocacaoController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new Alocacao($pdo);
    }

    public function index() {
        $msg = '';
        $msgType = '';
        $editData = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            try {
                if ($action === 'create') {
                    $this->model->create($_POST);
                    $msg = 'Alocação criada com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'update') {
                    $this->model->update($_POST['id'], $_POST);
                    $msg = 'Alocação atualizada com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'delete') {
                    $this->model->delete($_POST['id']);
                    $msg = 'Alocação excluída com sucesso!';
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

        $registros = $this->model->getAllWithJoin();
        $usuarios = (new Usuario($this->pdo))->getAll();
        $turmas = (new Turma($this->pdo))->getAll();
        $disciplinas = (new Disciplina($this->pdo))->getAll();

        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/sidebar.php';
        include __DIR__ . '/../views/alocacoes/index.php';
        include __DIR__ . '/../views/layout/footer.php';
    }
}
?>
