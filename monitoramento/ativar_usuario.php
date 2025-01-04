<?php
require 'db_connection.php';

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET ativo = 1 WHERE id = :id");
        $stmt->execute(['id' => $usuario_id]);
        header("Location: listar_usuarios.php"); // Retorna para a lista de usuários
    } catch (PDOException $e) {
        die("Erro ao ativar usuário: " . $e->getMessage());
    }
}
?>
