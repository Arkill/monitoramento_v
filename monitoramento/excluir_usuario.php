<?php
require 'db_connection.php';

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $usuario_id]);
        header("Location: monitoring.php");
    } catch (PDOException $e) {
        die("Erro ao excluir usuário: " . $e->getMessage());
    }
}
?>
