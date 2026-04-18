<?php
require_once __DIR__ . '/../model/database.php';
$pdo = getConnection();
$users = $pdo->query('SELECT * FROM usuarios')->fetchAll(PDO::FETCH_ASSOC);
echo '<pre>'; print_r($users); echo '</pre>';
?>