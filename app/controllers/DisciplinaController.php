<?php
// Controller: Disciplina
require_once __DIR__ . '/../models/Disciplina.php';

class DisciplinaController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Disciplina($pdo);
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
                    $msg = 'Disciplina criada com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'update') {
                    $this->model->update($_POST['id'], $_POST);
                    $msg = 'Disciplina atualizada com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'delete') {
                    $this->model->delete($_POST['id']);
                    $msg = 'Disciplina excluída com sucesso!';
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

        $disciplinas = $this->model->getAll();

        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/sidebar.php';
        include __DIR__ . '/../views/disciplinas/index.php';
        include __DIR__ . '/../views/layout/footer.php';
    }
}
?>
