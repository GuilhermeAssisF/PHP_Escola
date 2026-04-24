<?php
// Controller: Auth (Login / Logout)
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private $usuarioModel;

    public function __construct($pdo) {
        $this->usuarioModel = new Usuario($pdo);
    }

    public function loginForm() {
        $error = '';
        include __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                $error = 'Preencha todos os campos.';
                include __DIR__ . '/../views/auth/login.php';
                return;
            }

            $user = $this->usuarioModel->verifyPassword($email, $senha);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nome'] = $user['nome'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_perfil'] = $user['perfil'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'E-mail ou senha incorretos.';
                include __DIR__ . '/../views/auth/login.php';
                return;
            }
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    public function logout() {
        // Limpa todas as variáveis de sessão
        $_SESSION = [];

        // Deleta o cookie de sessão
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destrói a sessão
        session_destroy();

        header('Location: index.php?page=login');
        exit;
    }
}
?>
