<?php
session_start();
require 'db_connection.php'; // Arquivo de conexão com o banco de dados

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!empty($email) && !empty($senha)) {
        // Prepara a consulta para buscar o usuário com base no e-mail
        $stmt = $pdo->prepare("SELECT id, senha, tipo FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido - configura a sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // Redireciona com base no tipo de usuário
            switch ($usuario['tipo']) {
                case 'monitor':
                    header("Location: monitoring.php");
                    break;
                case 'admin':
                    header("Location: dashboard.php");
                    break;
                case 'cliente':
                    header("Location: moni.php");
                    break;
                default:
                    // Tipo de usuário inválido ou desconhecido
                    $error = 'Tipo de usuário inválido. Contate o suporte.';
                    break;
            }
            exit;
        } else {
            // E-mail ou senha incorretos
            $error = 'E-mail ou senha incorretos.';
        }
    } else {
        // Campos de login vazios
        $error = 'Preencha todos os campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <div id="container">
        <section>
            <article>
                <div class="inner">
                    <h1>Bem-vindo de volta!</h1>
                    <p>Por favor, entre com suas credenciais</p>
                    <a href="register.php" class="btn">Cadastrar-se</a>
                </div>
            </article>
            <aside>
                <div class="inner">
                    <h2>Login</h2>
                    <p>Use seu e-mail e senha para acessar</p>

                    <?php if (!empty($error)): ?>
                        <p class="error-message"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <form action="login.php" method="post">
                        <input type="email" name="email" class="input-field" placeholder="E-mail" required>
                        <input type="password" name="senha" class="input-field" placeholder="Senha" required>
                        <button type="submit">Entrar</button>
                        <p><a href="forgot_password.php" class="forgot-password-link">Esqueceu a senha?</a></p>
                    </form>
                </div>
            </aside>
        </section>
    </div>
</body>
</html>
