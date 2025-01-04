<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'monitor') {
    header("Location: login.php");
    exit;
}

require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $ativo = $_POST['ativo'];
    $dispositivos = $_POST['dispositivos'];
    $limite_dispositivos = $_POST['limite_dispositivos'];
    $data_validade = $_POST['data_validade'];

    // Validações dos campos
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email inválido.");
    }
    if (!is_numeric($dispositivos) || $dispositivos < 0) {
        die("Número de dispositivos inválido.");
    }
    if (!is_numeric($limite_dispositivos) || $limite_dispositivos < 0) {
        die("Limite de dispositivos inválido.");
    }
    if (empty($data_validade)) {
        die("Data de validade inválida.");
    }

    // Atualiza o banco de dados
    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET email = :email, ativo = :ativo, dispositivos = :dispositivos, limite_dispositivos = :limite_dispositivos, data_validade = :data_validade WHERE id = :id");
        $stmt->execute([
            ':email' => $email,
            ':ativo' => $ativo,
            ':dispositivos' => $dispositivos,
            ':limite_dispositivos' => $limite_dispositivos,
            ':data_validade' => $data_validade,
            ':id' => $id
        ]);

        // Redireciona com mensagem de sucesso
        header("Location: editar_usuario.php?id=$id&success=1");
        exit;
    } catch (PDOException $e) {
        die("Erro ao salvar as alterações: " . $e->getMessage());
    }
} else {
    die("Método de requisição inválido.");
}
