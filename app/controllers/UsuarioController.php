<?php
// Controller: Usuario
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private $model;

    public function __construct($pdo) {
        $this->model = new Usuario($pdo);
    }

    public function index() {
        $msg = '';
        $msgType = '';
        $editData = null;

        // Processar ações POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            try {
                if ($action === 'create') {
                    $this->model->create($_POST);
                    $msg = 'Usuário criado com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'update') {
                    $this->model->update($_POST['id'], $_POST);
                    $msg = 'Usuário atualizado com sucesso!';
                    $msgType = 'success';
                } elseif ($action === 'delete') {
                    $this->model->delete($_POST['id']);
                    $msg = 'Usuário excluído com sucesso!';
                    $msgType = 'success';
                }
            } catch (PDOException $e) {
                $msg = 'Erro: ' . $e->getMessage();
                $msgType = 'danger';
            }
        }

        // Carregar dados para edição
        if (isset($_GET['edit'])) {
            $editData = $this->model->getById($_GET['edit']);
        }

        $usuarios = $this->model->getAll();

        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/sidebar.php';
        include __DIR__ . '/../views/usuarios/index.php';
        include __DIR__ . '/../views/layout/footer.php';
    }
}
?>
