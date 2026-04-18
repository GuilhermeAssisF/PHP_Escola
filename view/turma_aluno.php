<?php
require_once __DIR__ . '/../model/database.php';

$msg = '';
$msgType = '';
$editData = null;

// Processa ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            $stmt = $pdo->prepare("INSERT INTO turma_aluno (turma_id, aluno_id) VALUES (?, ?)");
            $stmt->execute([$_POST['turma_id'], $_POST['aluno_id']]);
            $msg = 'Vínculo criado com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'update') {
            $stmt = $pdo->prepare("UPDATE turma_aluno SET turma_id = ?, aluno_id = ? WHERE id = ?");
            $stmt->execute([$_POST['turma_id'], $_POST['aluno_id'], $_POST['id']]);
            $msg = 'Vínculo atualizado com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM turma_aluno WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = 'Vínculo excluído com sucesso!';
            $msgType = 'success';
        }
    } catch (PDOException $e) {
        $msg = 'Erro: ' . $e->getMessage();
        $msgType = 'danger';
    }
}

// Carregar dados para edição
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM turma_aluno WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// Listar todos com JOIN
$registros = $pdo->query("
    SELECT ta.id, ta.turma_id, ta.aluno_id, t.nome AS turma_nome, a.nome AS aluno_nome, a.matricula
    FROM turma_aluno ta
    JOIN turmas t ON ta.turma_id = t.id
    JOIN alunos a ON ta.aluno_id = a.id
    ORDER BY ta.id DESC
")->fetchAll();

// Dados para selects
$turmas = $pdo->query("SELECT * FROM turmas ORDER BY nome")->fetchAll();
$alunos = $pdo->query("SELECT * FROM alunos ORDER BY nome")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turma-Aluno — Sistema Escolar</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>🔗 Turma-Aluno</h1>
        <p>Vincular alunos às turmas</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Vínculo' : '➕ Novo Vínculo' ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Turma</label>
                    <select name="turma_id" required>
                        <option value="">Selecione a turma...</option>
                        <?php foreach ($turmas as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= ($editData['turma_id'] ?? '') == $t['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nome']) ?> (<?= $t['ano_letivo'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Aluno</label>
                    <select name="aluno_id" required>
                        <option value="">Selecione o aluno...</option>
                        <?php foreach ($alunos as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= ($editData['aluno_id'] ?? '') == $a['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['nome']) ?> (<?= $a['matricula'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><?= $editData ? '💾 Atualizar' : '➕ Vincular' ?></button>
                <?php if ($editData): ?>
                    <a href="turma_aluno.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card">
        <h3 class="card-title">📋 Vínculos Turma-Aluno</h3>
        <?php if (empty($registros)): ?>
            <div class="empty-state">
                <div class="empty-icon">🔗</div>
                <p>Nenhum vínculo cadastrado ainda.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Turma</th>
                            <th>Aluno</th>
                            <th>Matrícula</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['turma_nome']) ?></td>
                            <td><?= htmlspecialchars($r['aluno_nome']) ?></td>
                            <td><?= htmlspecialchars($r['matricula']) ?></td>
                            <td class="actions">
                                <a href="turma_aluno.php?edit=<?= $r['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Deseja excluir este vínculo?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
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

