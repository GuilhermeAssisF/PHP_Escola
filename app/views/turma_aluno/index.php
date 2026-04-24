<?php $pageTitle = 'Turma-Aluno — Sistema Escolar'; ?>
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

    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Vínculo' : '➕ Novo Vínculo' ?></h3>
        <form method="POST" action="index.php?page=turma_aluno">
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
                    <a href="index.php?page=turma_aluno" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

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
                                <a href="index.php?page=turma_aluno&edit=<?= $r['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" action="index.php?page=turma_aluno" style="display:inline" onsubmit="return confirm('Deseja excluir este vínculo?')">
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
