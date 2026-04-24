<?php
// Controller: TurmaAluno
require_once __DIR__ . '/../models/TurmaAluno.php';
require_once __DIR__ . '/../models/Turma.php';
require_once __DIR__ . '/../models/Aluno.php';

class TurmaAlunoController {
    private $model;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->model = new TurmaAluno($pdo);
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
                    $msg = 'Vínculo criado com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'update') {
                    $this->model->update($_POST['id'], $_POST);
                    $msg = 'Vínculo atualizado com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'delete') {
                    $this->model->delete($_POST['id']);
                    $msg = 'Vínculo excluído com sucesso!';
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
        $turmas = (new Turma($this->pdo))->getAll();
        $alunos = (new Aluno($this->pdo))->getAll();

        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/sidebar.php';
        include __DIR__ . '/../views/turma_aluno/index.php';
        include __DIR__ . '/../views/layout/footer.php';
    }
}
?>
