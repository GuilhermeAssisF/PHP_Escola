<?php
require_once __DIR__ . '/../model/database.php';

$msg = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if ($email === '' || $senha === '') {
        $msg = 'Preencha email e senha.';
        $msgType = 'danger';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ? AND senha = ?');
        $stmt->execute([$email, $senha]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $msg = 'Login realizado com sucesso!';
            $msgType = 'success';
        } else {
            $msg = 'Email ou senha incorretos.';
            $msgType = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistema Escolar</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="main-content" style="max-width: 540px; margin: 40px auto;">
    <div class="page-header">
        <h1>🔐 Login</h1>
        <p>Entre com seu email e senha para acessar o sistema.</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3 class="card-title">Acesso ao sistema</h3>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="email@exemplo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" required placeholder="•••••••">
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Entrar</button>
                <a href="view/index.php" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
