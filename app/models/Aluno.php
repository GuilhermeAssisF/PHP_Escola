<?php
// Model: Aluno
class Aluno {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM alunos ORDER BY id DESC")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM alunos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO alunos (matricula, nome, data_nascimento) VALUES (?, ?, ?)");
        $stmt->execute([$data['matricula'], $data['nome'], $data['data_nascimento'] ?: null]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE alunos SET matricula = ?, nome = ?, data_nascimento = ? WHERE id = ?");
        $stmt->execute([$data['matricula'], $data['nome'], $data['data_nascimento'] ?: null, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM alunos WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function count() {
        return $this->pdo->query("SELECT COUNT(*) FROM alunos")->fetchColumn();
    }
}
?>
