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
            $stmt = $pdo->prepare("INSERT INTO turmas (nome, ano_letivo) VALUES (?, ?)");
            $stmt->execute([$_POST['nome'], $_POST['ano_letivo']]);
            $msg = 'Turma criada com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'update') {
            $stmt = $pdo->prepare("UPDATE turmas SET nome = ?, ano_letivo = ? WHERE id = ?");
            $stmt->execute([$_POST['nome'], $_POST['ano_letivo'], $_POST['id']]);
            $msg = 'Turma atualizada com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM turmas WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = 'Turma excluída com sucesso!';
            $msgType = 'success';
        }
    } catch (PDOException $e) {
        $msg = 'Erro: ' . $e->getMessage();
        $msgType = 'danger';
    }
}

// Carregar dados para edição
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM turmas WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// Listar todos
$turmas = $pdo->query("SELECT * FROM turmas ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turmas — Sistema Escolar</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>🏫 Turmas</h1>
        <p>Gerenciar turmas do sistema</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Turma' : '➕ Nova Turma' ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nome da Turma</label>
                    <input type="text" name="nome" required placeholder="Ex: 3º Ano A" value="<?= htmlspecialchars($editData['nome'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Ano Letivo</label>
                    <input type="number" name="ano_letivo" required placeholder="Ex: 2026" min="2000" max="2100" value="<?= htmlspecialchars($editData['ano_letivo'] ?? date('Y')) ?>">
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><?= $editData ? '💾 Atualizar' : '➕ Cadastrar' ?></button>
                <?php if ($editData): ?>
                    <a href="turmas.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card">
        <h3 class="card-title">📋 Lista de Turmas</h3>
        <?php if (empty($turmas)): ?>
            <div class="empty-state">
                <div class="empty-icon">🏫</div>
                <p>Nenhuma turma cadastrada ainda.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Ano Letivo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($turmas as $t): ?>
                        <tr>
                            <td><?= $t['id'] ?></td>
                            <td><?= htmlspecialchars($t['nome']) ?></td>
                            <td><?= $t['ano_letivo'] ?></td>
                            <td class="actions">
                                <a href="turmas.php?edit=<?= $t['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Deseja excluir esta turma?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
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

