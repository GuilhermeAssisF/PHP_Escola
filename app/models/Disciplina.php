<?php
// Model: Disciplina
class Disciplina {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM disciplinas ORDER BY id DESC")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM disciplinas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO disciplinas (nome) VALUES (?)");
        $stmt->execute([$data['nome']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE disciplinas SET nome = ? WHERE id = ?");
        $stmt->execute([$data['nome'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM disciplinas WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function count() {
        return $this->pdo->query("SELECT COUNT(*) FROM disciplinas")->fetchColumn();
    }
}
?>
