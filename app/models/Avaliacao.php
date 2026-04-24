<?php
// Model: Avaliacao
class Avaliacao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllWithJoin() {
        return $this->pdo->query("
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
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM avaliacoes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO avaliacoes (aluno_id, alocacao_id, nota_1, nota_2, faltas) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['aluno_id'], $data['alocacao_id'], $data['nota_1'], $data['nota_2'], $data['faltas']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE avaliacoes SET aluno_id = ?, alocacao_id = ?, nota_1 = ?, nota_2 = ?, faltas = ? WHERE id = ?");
        $stmt->execute([$data['aluno_id'], $data['alocacao_id'], $data['nota_1'], $data['nota_2'], $data['faltas'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM avaliacoes WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function count() {
        return $this->pdo->query("SELECT COUNT(*) FROM avaliacoes")->fetchColumn();
    }
}
?>
