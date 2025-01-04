<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'monitor') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT id, email FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $usuario_id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = 'cliente'; // Atualiza para o tipo cliente
            header("Location: dashboard_cliente.php"); // Redireciona para o painel do cliente
            exit;
        } else {
            echo "Usuário não encontrado.";
        }
    } catch (PDOException $e) {
        die("Erro ao logar como usuário: " . $e->getMessage());
    }
}
?>
