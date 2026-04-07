<?php
require_once 'database.php';

$msg = '';
$msgType = '';
$editData = null;

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            $stmt = $pdo->prepare("INSERT INTO alunos (matricula, nome, data_nascimento) VALUES (?, ?, ?)");
            $stmt->execute([$_POST['matricula'], $_POST['nome'], $_POST['data_nascimento'] ?: null]);
            $msg = 'Aluno criado com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'update') {
            $stmt = $pdo->prepare("UPDATE alunos SET matricula = ?, nome = ?, data_nascimento = ? WHERE id = ?");
            $stmt->execute([$_POST['matricula'], $_POST['nome'], $_POST['data_nascimento'] ?: null, $_POST['id']]);
            $msg = 'Aluno atualizado com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM alunos WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = 'Aluno excluído com sucesso!';
            $msgType = 'success';
        }
    } catch (PDOException $e) {
        $msg = 'Erro: ' . $e->getMessage();
        $msgType = 'danger';
    }
}

// Carregar dados para edição
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM alunos WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// Listar todos
$alunos = $pdo->query("SELECT * FROM alunos ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alunos — Sistema Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>🎓 Alunos</h1>
        <p>Gerenciar alunos matriculados</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário WEB-->
    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Aluno' : '➕ Novo Aluno' ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group">
                    <label>Matrícula</label>
                    <input type="text" name="matricula" required placeholder="Ex: 2024001" value="<?= htmlspecialchars($editData['matricula'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="nome" required placeholder="Nome completo do aluno" value="<?= htmlspecialchars($editData['nome'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Data de Nascimento</label>
                    <input type="date" name="data_nascimento" value="<?= htmlspecialchars($editData['data_nascimento'] ?? '') ?>">
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><?= $editData ? '💾 Atualizar' : '➕ Cadastrar' ?></button>
                <?php if ($editData): ?>
                    <a href="alunos.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card">
        <h3 class="card-title">📋 Lista de Alunos</h3>
        <?php if (empty($alunos)): ?>
            <div class="empty-state">
                <div class="empty-icon">🎓</div>
                <p>Nenhum aluno cadastrado ainda.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Data Nasc.</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alunos as $a): ?>
                        <tr>
                            <td><?= $a['id'] ?></td>
                            <td><?= htmlspecialchars($a['matricula']) ?></td>
                            <td><?= htmlspecialchars($a['nome']) ?></td>
                            <td><?= $a['data_nascimento'] ? date('d/m/Y', strtotime($a['data_nascimento'])) : '—' ?></td>
                            <td class="actions">
                                <a href="alunos.php?edit=<?= $a['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Deseja excluir este aluno?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $a['id'] ?>">
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
