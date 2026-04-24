<?php $pageTitle = 'Usuários — Sistema Escolar'; ?>
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
        <form method="POST" action="index.php?page=usuarios">
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
                    <label>Senha <?= $editData ? '(deixe vazio para manter)' : '' ?></label>
                    <input type="password" name="senha" <?= $editData ? '' : 'required' ?> placeholder="•••••••">
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
                    <a href="index.php?page=usuarios" class="btn btn-danger">Cancelar</a>
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
                                <a href="index.php?page=usuarios&edit=<?= $u['id'] ?>" class="btn btn-primary btn-sm">✏️ Editar</a>
                                <form method="POST" action="index.php?page=usuarios" style="display:inline" onsubmit="return confirm('Deseja excluir este usuário?')">
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
