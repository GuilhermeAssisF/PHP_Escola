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
            $stmt = $pdo->prepare("INSERT INTO disciplinas (nome) VALUES (?)");
            $stmt->execute([$_POST['nome']]);
            $msg = 'Disciplina criada com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'update') {
            $stmt = $pdo->prepare("UPDATE disciplinas SET nome = ? WHERE id = ?");
            $stmt->execute([$_POST['nome'], $_POST['id']]);
            $msg = 'Disciplina atualizada com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM disciplinas WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = 'Disciplina excluída com sucesso!';
            $msgType = 'success';
        }
    } catch (PDOException $e) {
        $msg = 'Erro: ' . $e->getMessage();
        $msgType = 'danger';
    }
}

// Carregar dados para edição
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM disciplinas WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// Listar todos
$disciplinas = $pdo->query("SELECT * FROM disciplinas ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disciplinas — Sistema Escolar</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>📚 Disciplinas</h1>
        <p>Gerenciar disciplinas do currículo</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Disciplina' : '➕ Nova Disciplina' ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome da Disciplina</label>
                    <input type="text" name="nome" required placeholder="Ex: Matemática" value="<?= htmlspecialchars($editData['nome'] ?? '') ?>">
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><?= $editData ? '💾 Atualizar' : '➕ Cadastrar' ?></button>
                <?php if ($editData): ?>
                    <a href="disciplinas.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card">
        <h3 class="card-title">📋 Lista de Disciplinas</h3>
        <?php if (empty($disciplinas)): ?>
            <div class="empty-state">
                <div class="empty-icon">📚</div>
                <p>Nenhuma disciplina cadastrada ainda.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($disciplinas as $d): ?>
                        <tr>
                            <td><?= $d['id'] ?></td>
                            <td><?= htmlspecialchars($d['nome']) ?></td>
                            <td class="actions">
                                <a href="disciplinas.php?edit=<?= $d['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Deseja excluir esta disciplina?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
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

