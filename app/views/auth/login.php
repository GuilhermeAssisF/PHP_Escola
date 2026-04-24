<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistema Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">🎓</div>
            <h1>Sistema Escolar</h1>
            <p>Faça login para acessar o sistema</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="login-error">
                <span>❌</span> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=login" class="login-form" id="loginForm">
            <div class="form-group">
                <label for="email">📧 E-mail</label>
                <input type="email" name="email" id="email" required placeholder="seu@email.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="email">
            </div>
            <div class="form-group">
                <label for="senha">🔒 Senha</label>
                <input type="password" name="senha" id="senha" required placeholder="••••••••" autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary btn-login" id="btnLogin">
                🔑 Entrar
            </button>
        </form>

        <div class="login-footer">
            <p>Credenciais padrão: <strong>admin@escola.com</strong> / <strong>admin123</strong></p>
        </div>
    </div>
</div>

</body>
</html>
