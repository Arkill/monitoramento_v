<?php
session_start();

// Verifica se o usuário está logado e é do tipo monitor ou admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'monitor') {
    header("Location: login.php");
    exit;
}

require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo']; // Tipo do usuário (monitor, admin, etc.)

    // Valida os dados do formulário
    if (empty($email) || empty($senha) || empty($tipo)) {
        $error = "Todos os campos são obrigatórios.";
    } else {
        try {
            // Insere o usuário no banco de dados
            $stmt = $pdo->prepare("INSERT INTO usuarios (email, senha, tipo, ativo) VALUES (:email, :senha, :tipo, 1)");
            $stmt->execute([
                ':email' => $email,
                ':senha' => password_hash($senha, PASSWORD_BCRYPT),
                ':tipo' => $tipo
            ]);

            // Redireciona para a página de monitoramento com mensagem de sucesso
            header("Location: monitoring.php?success=1");
            exit;
        } catch (PDOException $e) {
            $error = "Erro ao adicionar o usuário: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Adicionar Usuário</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de Usuário:</label>
                <select name="tipo" id="tipo" class="form-control" required>
                    <option value="monitor">Monitor</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Adicionar</button>
                <a href="monitoring.php" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>
</body>
</html>
