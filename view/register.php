<?php
require_once __DIR__ . '/../model/database.php';
$pdo = getConnection();

$msg = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $perfil = trim($_POST['perfil'] ?? 'professor');

    if ($nome === '' || $email === '' || $senha === '') {
        $msg = 'Preencha todos os campos obrigatórios.';
        $msgType = 'danger';
    } else {
        try {
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)');
            $stmt->execute([$nome, $email, $senhaHash, $perfil]);
            $msg = 'Usuário cadastrado com sucesso!';
            $msgType = 'success';
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'UNIQUE') !== false) {
                $msg = 'Este email já está cadastrado.';
            } else {
                $msg = 'Erro ao cadastrar usuário: ' . $e->getMessage();
            }
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
    <title>Cadastro de Usuário — Sistema Escolar</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="main-content" style="max-width: 600px; margin: 40px auto;">
    <div class="page-header">
        <h1>➕ Cadastro de Usuário</h1>
        <p>Crie uma conta para acessar o sistema.</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3 class="card-title">Dados do usuário</h3>
        <form method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" required placeholder="Nome completo" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="email@exemplo.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" required placeholder="Digite uma senha">
                </div>
                <div class="form-group">
                    <label>Perfil</label>
                    <select name="perfil" required>
                        <option value="professor" <?= (($_POST['perfil'] ?? '') === 'professor') ? 'selected' : '' ?>>Professor</option>
                        <option value="admin" <?= (($_POST['perfil'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Cadastrar usuário</button>
                <a href="index.php" class="btn btn-primary">Voltar ao login</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
