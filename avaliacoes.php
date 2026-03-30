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
            $stmt = $pdo->prepare("INSERT INTO avaliacoes (aluno_id, alocacao_id, nota_1, nota_2, faltas) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['aluno_id'], $_POST['alocacao_id'], $_POST['nota_1'], $_POST['nota_2'], $_POST['faltas']]);
            $msg = 'Avaliação criada com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'update') {
            $stmt = $pdo->prepare("UPDATE avaliacoes SET aluno_id = ?, alocacao_id = ?, nota_1 = ?, nota_2 = ?, faltas = ? WHERE id = ?");
            $stmt->execute([$_POST['aluno_id'], $_POST['alocacao_id'], $_POST['nota_1'], $_POST['nota_2'], $_POST['faltas'], $_POST['id']]);
            $msg = 'Avaliação atualizada com sucesso!';
            $msgType = 'success';
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM avaliacoes WHERE id = ?");
            $stmt->execute([$_POST['id']]);
            $msg = 'Avaliação excluída com sucesso!';
            $msgType = 'success';
        }
    } catch (PDOException $e) {
        $msg = 'Erro: ' . $e->getMessage();
        $msgType = 'danger';
    }
}

// Carregar dados para edição
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM avaliacoes WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// Listar todos com JOIN
$registros = $pdo->query("
    SELECT av.id, av.aluno_id, av.alocacao_id, av.nota_1, av.nota_2, av.faltas,
           a.nome AS aluno_nome, a.matricula,
           d.nome AS disciplina_nome, t.nome AS turma_nome
    FROM avaliacoes av
    JOIN alunos a ON av.aluno_id = a.id
    JOIN alocacoes al ON av.alocacao_id = al.id
    JOIN disciplinas d ON al.disciplina_id = d.id
    JOIN turmas t ON al.turma_id = t.id
    ORDER BY av.id DESC
")->fetchAll();

// Dados para selects
$alunos = $pdo->query("SELECT * FROM alunos ORDER BY nome")->fetchAll();
$alocacoes = $pdo->query("
    SELECT al.id, u.nome AS professor, t.nome AS turma, d.nome AS disciplina
    FROM alocacoes al
    JOIN usuarios u ON al.usuario_id = u.id
    JOIN turmas t ON al.turma_id = t.id
    JOIN disciplinas d ON al.disciplina_id = d.id
    ORDER BY d.nome
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliações — Sistema Escolar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="page-header">
        <h1>📝 Avaliações</h1>
        <p>Registrar notas e faltas dos alunos</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Avaliação' : '➕ Nova Avaliação' ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>
            <div class="form-grid">
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
                <div class="form-group">
                    <label>Alocação (Disciplina / Turma / Professor)</label>
                    <select name="alocacao_id" required>
                        <option value="">Selecione a alocação...</option>
                        <?php foreach ($alocacoes as $al): ?>
                            <option value="<?= $al['id'] ?>" <?= ($editData['alocacao_id'] ?? '') == $al['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($al['disciplina']) ?> — <?= htmlspecialchars($al['turma']) ?> (Prof. <?= htmlspecialchars($al['professor']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nota 1</label>
                    <input type="number" name="nota_1" required step="0.1" min="0" max="10" placeholder="0.0" value="<?= htmlspecialchars($editData['nota_1'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Nota 2</label>
                    <input type="number" name="nota_2" required step="0.1" min="0" max="10" placeholder="0.0" value="<?= htmlspecialchars($editData['nota_2'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Faltas</label>
                    <input type="number" name="faltas" required min="0" placeholder="0" value="<?= htmlspecialchars($editData['faltas'] ?? '') ?>">
                </div>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-primary"><?= $editData ? '💾 Atualizar' : '➕ Cadastrar' ?></button>
                <?php if ($editData): ?>
                    <a href="avaliacoes.php" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="card">
        <h3 class="card-title">📋 Lista de Avaliações</h3>
        <?php if (empty($registros)): ?>
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <p>Nenhuma avaliação cadastrada ainda.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Aluno</th>
                            <th>Disciplina</th>
                            <th>Turma</th>
                            <th>Nota 1</th>
                            <th>Nota 2</th>
                            <th>Média</th>
                            <th>Faltas</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $r):
                            $media = ($r['nota_1'] + $r['nota_2']) / 2;
                            $mediaClass = $media >= 6 ? 'success' : 'danger';
                        ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['aluno_nome']) ?></td>
                            <td><?= htmlspecialchars($r['disciplina_nome']) ?></td>
                            <td><?= htmlspecialchars($r['turma_nome']) ?></td>
                            <td><?= number_format($r['nota_1'], 1) ?></td>
                            <td><?= number_format($r['nota_2'], 1) ?></td>
                            <td><span class="badge badge-<?= $mediaClass ?>" style="background: rgba(<?= $media >= 6 ? '0,201,167' : '255,107,107' ?>, 0.15); color: var(--<?= $mediaClass ?>);"><?= number_format($media, 1) ?></span></td>
                            <td><?= $r['faltas'] ?></td>
                            <td class="actions">
                                <a href="avaliacoes.php?edit=<?= $r['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" style="display:inline" onsubmit="return confirm('Deseja excluir esta avaliação?')">
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
