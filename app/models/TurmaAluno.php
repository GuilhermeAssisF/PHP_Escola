<?php
// Model: TurmaAluno
class TurmaAluno {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllWithJoin() {
        return $this->pdo->query("
            SELECT ta.id, ta.turma_id, ta.aluno_id,
                   t.nome AS turma_nome, a.nome AS aluno_nome, a.matricula
            FROM turma_aluno ta
            JOIN turmas t ON ta.turma_id = t.id
            JOIN alunos a ON ta.aluno_id = a.id
            ORDER BY ta.id DESC
        ")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM turma_aluno WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO turma_aluno (turma_id, aluno_id) VALUES (?, ?)");
        $stmt->execute([$data['turma_id'], $data['aluno_id']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE turma_aluno SET turma_id = ?, aluno_id = ? WHERE id = ?");
        $stmt->execute([$data['turma_id'], $data['aluno_id'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM turma_aluno WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
