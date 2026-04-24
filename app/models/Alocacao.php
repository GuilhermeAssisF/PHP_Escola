<?php
// Model: Alocacao
class Alocacao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllWithJoin() {
        return $this->pdo->query("
            SELECT al.id, al.usuario_id, al.turma_id, al.disciplina_id,
                   u.nome AS usuario_nome, t.nome AS turma_nome, d.nome AS disciplina_nome
            FROM alocacoes al
            JOIN usuarios u ON al.usuario_id = u.id
            JOIN turmas t ON al.turma_id = t.id
            JOIN disciplinas d ON al.disciplina_id = d.id
            ORDER BY al.id DESC
        ")->fetchAll();
    }

    public function getAllForSelect() {
        return $this->pdo->query("
            SELECT al.id, u.nome AS professor, t.nome AS turma, d.nome AS disciplina
            FROM alocacoes al
            JOIN usuarios u ON al.usuario_id = u.id
            JOIN turmas t ON al.turma_id = t.id
            JOIN disciplinas d ON al.disciplina_id = d.id
            ORDER BY d.nome
        ")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM alocacoes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO alocacoes (usuario_id, turma_id, disciplina_id) VALUES (?, ?, ?)");
        $stmt->execute([$data['usuario_id'], $data['turma_id'], $data['disciplina_id']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE alocacoes SET usuario_id = ?, turma_id = ?, disciplina_id = ? WHERE id = ?");
        $stmt->execute([$data['usuario_id'], $data['turma_id'], $data['disciplina_id'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM alocacoes WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function count() {
        return $this->pdo->query("SELECT COUNT(*) FROM alocacoes")->fetchColumn();
    }

    /**
     * Retorna alocações filtradas por usuario_id (para o select do professor)
     */
    public function getForSelectByUsuario($usuarioId) {
        $stmt = $this->pdo->prepare("
            SELECT al.id, u.nome AS professor, t.nome AS turma, d.nome AS disciplina, al.turma_id
            FROM alocacoes al
            JOIN usuarios u ON al.usuario_id = u.id
            JOIN turmas t ON al.turma_id = t.id
            JOIN disciplinas d ON al.disciplina_id = d.id
            WHERE al.usuario_id = ?
            ORDER BY d.nome
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna os alunos das turmas em que o professor leciona
     */
    public function getAlunosByUsuario($usuarioId) {
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT a.id, a.nome, a.matricula
            FROM alunos a
            JOIN turma_aluno ta ON a.id = ta.aluno_id
            JOIN alocacoes al ON ta.turma_id = al.turma_id
            WHERE al.usuario_id = ?
            ORDER BY a.nome
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna avaliações filtradas pelo professor (via alocacoes)
     */
    public function getAvaliacoesByUsuario($usuarioId) {
        $stmt = $this->pdo->prepare("
            SELECT av.id, av.aluno_id, av.alocacao_id, av.nota_1, av.nota_2, av.faltas,
                   a.nome AS aluno_nome, a.matricula,
                   d.nome AS disciplina_nome, t.nome AS turma_nome
            FROM avaliacoes av
            JOIN alunos a ON av.aluno_id = a.id
            JOIN alocacoes al ON av.alocacao_id = al.id
            JOIN disciplinas d ON al.disciplina_id = d.id
            JOIN turmas t ON al.turma_id = t.id
            WHERE al.usuario_id = ?
            ORDER BY av.id DESC
        ");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }
}
?>
