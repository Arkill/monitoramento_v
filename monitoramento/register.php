<?php
session_start();
$error = ''; // Para armazenar mensagens de erro

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $confirmaSenha = trim($_POST['confirma_senha'] ?? '');

    // Verifica se todos os campos foram preenchidos
    if (!empty($nome) && !empty($email) && !empty($senha) && !empty($confirmaSenha)) {
        // Verifica se as senhas coincidem
        if ($senha === $confirmaSenha) {
            require 'db_connection.php'; // Inclui o arquivo de conexão com o banco

            // Verifica se o e-mail já está cadastrado na tabela `usuarios`
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);

            if ($stmt->rowCount() > 0) {
                $error = 'E-mail já cadastrado!'; // Caso o e-mail já exista
            } else {
                // Insere os dados na tabela `clientes`
                $stmt = $pdo->prepare("INSERT INTO clientes (nome) VALUES (:nome)");
                $stmt->execute(['nome' => $nome]);

                // Obtém o ID do cliente recém-criado
                $clienteId = $pdo->lastInsertId();

                // Insere os dados na tabela `usuarios`
                $hashSenha = password_hash($senha, PASSWORD_BCRYPT); // Hash da senha
                $stmt = $pdo->prepare("INSERT INTO usuarios (email, senha, tipo, cliente_id) VALUES (:email, :senha, 'cliente', :cliente_id)");
                $stmt->execute([
                    'email' => $email,
                    'senha' => $hashSenha,
                    'cliente_id' => $clienteId
                ]);

                // Redireciona para a tela de login após o cadastro
                header('Location: login.php');
                exit();
            }
        } else {
            $error = 'As senhas não coincidem.'; // Mensagem de erro se as senhas não coincidirem
        }
    } else {
        $error = 'Por favor, preencha todos os campos.'; // Mensagem de erro se algum campo estiver vazio
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="container">
        <section>
            <article>
                <div class="inner">
                    <h1>Bem-vindo ao nosso sistema!</h1>
                    <p>Por favor, preencha os dados abaixo para se cadastrar</p>
                    <a href="login.php" class="btn">Fazer Login</a>
                </div>
            </article>
            <aside>
                <div class="inner">
                    <h2>Cadastro</h2>
                    <p>Crie sua conta preenchendo as informações abaixo</p>

                    <?php if (!empty($error)): ?>
                        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>

                    <form action="register.php" method="post">
                        <input type="text" name="nome" placeholder="Nome Completo" required>
                        <input type="email" name="email" placeholder="E-mail" required>
                        <input type="password" name="senha" placeholder="Senha" required>
                        <input type="password" name="confirma_senha" placeholder="Confirme sua Senha" required>
                        <button type="submit">Cadastrar</button>
                    </form>
                </div>
            </aside>
        </section>
    </div>
</body>
</html>
