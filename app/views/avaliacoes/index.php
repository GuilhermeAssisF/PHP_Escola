<?php $pageTitle = 'Avaliações — Sistema Escolar'; ?>
<div class="main-content">
    <div class="page-header">
        <h1>📝 Avaliações</h1>
        <p>Registrar notas e faltas dos alunos das suas turmas</p>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msgType ?>">
            <?= $msgType === 'success' ? '✅' : '❌' ?> <?= htmlspecialchars($msg) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($alocacoes)): ?>
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📋</div>
                <p>Você ainda não possui alocações (disciplinas/turmas) atribuídas.</p>
                <p style="font-size: 0.8rem; margin-top: 8px; opacity: 0.7;">Solicite ao administrador para vincular você a uma turma e disciplina.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <h3 class="card-title"><?= $editData ? '✏️ Editar Avaliação' : '➕ Nova Avaliação' ?></h3>
            <form method="POST" action="index.php?page=avaliacoes">
                <input type="hidden" name="action" value="<?= $editData ? 'update' : 'create' ?>">
                <?php if ($editData): ?>
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                <?php endif; ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Disciplina / Turma</label>
                        <select name="alocacao_id" required>
                            <option value="">Selecione a disciplina...</option>
                            <?php foreach ($alocacoes as $al): ?>
                                <option value="<?= $al['id'] ?>" <?= ($editData['alocacao_id'] ?? '') == $al['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($al['disciplina']) ?> — <?= htmlspecialchars($al['turma']) ?>
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
                        <a href="index.php?page=avaliacoes" class="btn btn-danger">Cancelar</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3 class="card-title">📋 Suas Avaliações</h3>
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
                                <a href="index.php?page=avaliacoes&edit=<?= $r['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" action="index.php?page=avaliacoes" style="display:inline" onsubmit="return confirm('Deseja excluir esta avaliação?')">
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
