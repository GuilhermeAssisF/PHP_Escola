<?php
require_once __DIR__ . '/../model/database.php';

$msg = '';
$msgType = '';
$editData = null;

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            $hashedPassword = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['nome'], $_POST['email'], $hashedPassword, $_POST['perfil']]);
            $msg = 'Usuário criado com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'update') {
            $senha = trim($_POST['senha'] ?? '');
            if ($senha === '') {
                $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $senhaHash = $stmt->fetchColumn();
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            }
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ?, perfil = ? WHERE id = ?");
            $stmt->execute([$_POST['nome'], $_POST['email'], $senhaHash, $_POST['perfil'], $_POST['id']]);
            $msg = 'Usuário atualizado com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$_POST['id']]);
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
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// Listar todos
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários — Sistema Escolar</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>👤 Usuários</h1>
        <p>Gerenciar usuários do sistema</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Usuário' : '➕ Novo Usuário' ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" required placeholder="Nome completo" value="<?= htmlspecialchars($editData['nome'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="email@exemplo.com" value="<?= htmlspecialchars($editData['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input type="password" name="senha" placeholder="•••••••">
                    <?php if ($editData): ?>
                        <small>Deixe em branco para manter a senha atual.</small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Perfil</label>
                    <select name="perfil" required>
                        <option value="">Selecione...</option>
                        <option value="admin" <?= ($editData['perfil'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="professor" <?= ($editData['perfil'] ?? '') === 'professor' ? 'selected' : '' ?>>Professor</option>
                    </select>
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><?= $editData ? '💾 Atualizar' : '➕ Cadastrar' ?></button>
                <?php if ($editData): ?>
                    <a href="usuarios.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card">
        <h3 class="card-title">📋 Lista de Usuários</h3>
        <?php if (empty($usuarios)): ?>
            <div class="empty-state">
                <div class="empty-icon">👤</div>
                <p>Nenhum usuário cadastrado ainda.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><?= $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['nome']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><span class="badge badge-<?= $u['perfil'] ?>"><?= $u['perfil'] ?></span></td>
                            <td class="actions">
                                <a href="usuarios.php?edit=<?= $u['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Deseja excluir este usuário?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">🗑️ Excluir</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

