<?php $pageTitle = 'Alocações — Sistema Escolar'; ?>
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

    <div class="card">
        <h3 class="card-title"><?= $editData ? '✏️ Editar Alocação' : '➕ Nova Alocação' ?></h3>
        <form method="POST" action="index.php?page=alocacoes">
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
                    <a href="index.php?page=alocacoes" class="btn btn-danger">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

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
                                <a href="index.php?page=alocacoes&edit=<?= $r['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" action="index.php?page=alocacoes" style="display:inline" onsubmit="return confirm('Deseja excluir esta alocação?')">
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
