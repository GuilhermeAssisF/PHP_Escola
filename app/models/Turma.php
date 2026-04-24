<?php
// Model: Turma
class Turma {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM turmas ORDER BY id DESC")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM turmas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO turmas (nome, ano_letivo) VALUES (?, ?)");
        $stmt->execute([$data['nome'], $data['ano_letivo']]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE turmas SET nome = ?, ano_letivo = ? WHERE id = ?");
        $stmt->execute([$data['nome'], $data['ano_letivo'], $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM turmas WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function count() {
        return $this->pdo->query("SELECT COUNT(*) FROM turmas")->fetchColumn();
    }
}
?>
