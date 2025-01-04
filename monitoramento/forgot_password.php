<?php
require 'db_connection.php';

$message = ''; // Variável para mensagens de erro ou sucesso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (!empty($email)) {
        try {
            // Verificar se o e-mail existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Gerar token único e armazenar no banco de dados
                $token = bin2hex(random_bytes(16));
                $stmt = $pdo->prepare("UPDATE usuarios SET reset_token = :token WHERE email = :email");
                $stmt->execute([':token' => $token, ':email' => $email]);

                $message = "<div class='alert success'>
                                <span class='closebtn' onclick='this.parentElement.style.display=\"none\";'>&times;</span> 
                                <strong>Sucesso!</strong> Token de redefinição gerado com sucesso!<br> 
                                Use este link para redefinir sua senha: <a href='reset_password.php?token=$token'>Clique aqui</a>
                            </div>";
            } else {
                $message = "<div class='alert error'>
                                <span class='closebtn' onclick='this.parentElement.style.display=\"none\";'>&times;</span>
                                <strong>Erro!</strong> E-mail não encontrado.
                            </div>";
            }
        } catch (PDOException $e) {
            $message = "<div class='alert error'>
                            <span class='closebtn' onclick='this.parentElement.style.display=\"none\";'>&times;</span>
                            <strong>Erro!</strong> " . htmlspecialchars($e->getMessage()) . "
                        </div>";
        }
    } else {
        $message = "<div class='alert error'>
                        <span class='closebtn' onclick='this.parentElement.style.display=\"none\";'>&times;</span>
                        <strong>Erro!</strong> Por favor, insira seu e-mail.
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha</title>
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

        form input[type=email] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            font-size: 18px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        form button[type=submit] {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            color: #fff;
            background-color: #00bcf5;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        form button[type=submit]:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="inner">
            <h1>Esqueceu a Senha</h1>
            <p>Insira seu e-mail para criar uma nova senha.</p>

            <!-- Exibir a mensagem de erro ou sucesso -->
            <?= $message ?>

            <!-- Formulário para inserir o e-mail -->
            <form method="POST">
                <input type="email" name="email" placeholder="Digite seu e-mail" required>
                <button type="submit">Solicitar Redefinição</button>
            </form>
        </div>
    </div>

    <script>
        // Script opcional para controlar os alertas ou outras interações, se necessário
    </script>
</body>
</html>
