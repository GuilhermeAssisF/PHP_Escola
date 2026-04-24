<?php
// Model: Usuario
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data) {
        $hash = password_hash($data['senha'], PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha, perfil) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['nome'], $data['email'], $hash, $data['perfil']]);
    }

    public function update($id, $data) {
        // Se senha foi informada, criptografa; senão mantém a atual
        if (!empty($data['senha'])) {
            $hash = password_hash($data['senha'], PASSWORD_BCRYPT);
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ?, perfil = ? WHERE id = ?");
            $stmt->execute([$data['nome'], $data['email'], $hash, $data['perfil'], $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, perfil = ? WHERE id = ?");
            $stmt->execute([$data['nome'], $data['email'], $data['perfil'], $id]);
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function verifyPassword($email, $senha) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($senha, $user['senha'])) {
            return $user;
        }
        return false;
    }
}
?>
