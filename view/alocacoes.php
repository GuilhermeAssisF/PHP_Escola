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
            $stmt = $pdo->prepare("INSERT INTO alocacoes (usuario_id, turma_id, disciplina_id) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['usuario_id'], $_POST['turma_id'], $_POST['disciplina_id']]);
            $msg = 'Alocação criada com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'update') {
            $stmt = $pdo->prepare("UPDATE alocacoes SET usuario_id = ?, turma_id = ?, disciplina_id = ? WHERE id = ?");
            $stmt->execute([$_POST['usuario_id'], $_POST['turma_id'], $_POST['disciplina_id'], $_POST['id']]);
            $msg = 'Alocação atualizada com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM alocacoes WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = 'Alocação excluída com sucesso!';
            $msgType = 'success';
        }
    } catch (PDOException $e) {
        $msg = 'Erro: ' . $e->getMessage();
        $msgType = 'danger';
    }
}

// Carregar dados para edição
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM alocacoes WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// Listar todos com JOIN
$registros = $pdo->query("
    SELECT al.id, al.usuario_id, al.turma_id, al.disciplina_id,
           u.nome AS usuario_nome, t.nome AS turma_nome, d.nome AS disciplina_nome
    FROM alocacoes al
    JOIN usuarios u ON al.usuario_id = u.id
    JOIN turmas t ON al.turma_id = t.id
    JOIN disciplinas d ON al.disciplina_id = d.id
    ORDER BY al.id DESC
")->fetchAll();

// Dados para selects
$usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY nome")->fetchAll();
$turmas = $pdo->query("SELECT * FROM turmas ORDER BY nome")->fetchAll();
$disciplinas = $pdo->query("SELECT * FROM disciplinas ORDER BY nome")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alocações — Sistema Escolar</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>📋 Alocações</h1>
        <p>Vincular professores a turmas e disciplinas</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Alocação' : '➕ Nova Alocação' ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Professor / Usuário</label>
                    <select name="usuario_id" required>
                        <option value="">Selecione o professor...</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= ($editData['usuario_id'] ?? '') == $u['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['nome']) ?> (<?= $u['perfil'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                    <label>Disciplina</label>
                    <select name="disciplina_id" required>
                        <option value="">Selecione a disciplina...</option>
                        <?php foreach ($disciplinas as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= ($editData['disciplina_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><?= $editData ? '💾 Atualizar' : '➕ Cadastrar' ?></button>
                <?php if ($editData): ?>
                    <a href="alocacoes.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card">
        <h3 class="card-title">📋 Lista de Alocações</h3>
        <?php if (empty($registros)): ?>
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <p>Nenhuma alocação cadastrada ainda.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Professor</th>
                            <th>Turma</th>
                            <th>Disciplina</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['usuario_nome']) ?></td>
                            <td><?= htmlspecialchars($r['turma_nome']) ?></td>
                            <td><?= htmlspecialchars($r['disciplina_nome']) ?></td>
                            <td class="actions">
                                <a href="alocacoes.php?edit=<?= $r['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Deseja excluir esta alocação?')">
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

