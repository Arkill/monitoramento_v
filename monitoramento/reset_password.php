<?php
require 'db_connection.php';

$error = '';
$success = '';

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Verificar se o token existe no banco de dados
    try {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE reset_token = :token");
        $stmt->execute([':token' => $token]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Token válido, agora o usuário pode redefinir a senha
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $novaSenha = $_POST['nova_senha'];
                
                if (!empty($novaSenha)) {
                    // Hash da nova senha
                    $hashSenha = password_hash($novaSenha, PASSWORD_DEFAULT);

                    // Atualizar a senha no banco de dados e limpar o token
                    $stmt = $pdo->prepare("UPDATE usuarios SET senha = :senha, reset_token = NULL WHERE id = :id");
                    $stmt->execute([':senha' => $hashSenha, ':id' => $usuario['id']]);

                    $success = "Senha redefinida com sucesso! <br> <a href='login.php' class='reset-link'>Faça login</a>";

                    // Redirecionar para a página de login após 5 segundos
                    header("refresh:2;url=login.php"); // Redireciona para a página de login após 5 segundos
                    exit();
                } else {
                    $error = "Por favor, insira uma nova senha.";
                }
            }
        } else {
            $error = "Token inválido ou expirado.";
        }
    } catch (PDOException $e) {
        $error = "Erro ao processar a redefinição de senha: " . $e->getMessage();
    }
} else {
    $error = "Token não fornecido.";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="r.css">
    <style>
        /* Estilo dos alertas */
        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            position: relative;
            font-size: 16px;
        }

        .alert.success {
            background-color: #4CAF50;
            color: white;
        }

        .alert.error {
            background-color: #f44336;
            color: white;
        }

        .alert .closebtn {
            position: absolute;
            top: 0;
            right: 15px;
            color: white;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
        }

        .alert .closebtn:hover {
            color: #ddd;
        }

        /* Formatação do formulário */
        form {
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
        }

        form input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            font-size: 18px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        form button[type=submit] {
            margin-top: 20px;
            border: none;
            background-color: #00bcf5;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            padding: 10px 30px;
            border-radius: 25px;
            transition: 0.2s all ease;
        }

        form button[type=submit]:hover {
            background-color: #000;
            color: #00acf5;
        }

        /* Link de login sem estilo */
        a.reset-link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        a.reset-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="inner">
            <h1>Redefinir Senha</h1>

            <!-- Exibir mensagem de erro ou sucesso -->
            <?php if ($error): ?>
                <div class="alert error">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <strong>Erro!</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert success">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <strong>Sucesso!</strong> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <!-- Formulário para redefinir a senha -->
            <form method="POST">
                <input type="password" name="nova_senha" placeholder="Digite sua nova senha" required>
                <button type="submit">Redefinir Senha</button>
            </form>
        </div>
    </div>

    <script>
        // Script opcional para controlar os alertas ou outras interações, se necessário
    </script>
</body>
</html>
