<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'monitor') {
    header("Location: login.php");
    exit;
}

// Obtém o e-mail do usuário logado
require 'db_connection.php'; // Conexão com o banco de dados
try {
    $stmt = $pdo->prepare("SELECT email FROM usuarios WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['usuario_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $user['email'] ?? '';

    // Busca todos os usuários do banco de dados
    $stmtUsers = $pdo->prepare("SELECT id, email, ativo, dispositivos, limite_dispositivos, data_validade, ultimo_login FROM usuarios");
    $stmtUsers->execute();
    $usuarios = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar informações do usuário: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sentinela - Sistema de Monitoramento</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="n_estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-layout">
    <div class="header">
        <nav class="navbar navbar-main navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" 
                            data-toggle="collapse" 
                            data-target="#bs-header-navbar-collapse" 
                            aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">
                        <img src="img/R.png" alt="Logo">
                    </a>
                    <p class="navbar-text">Sentinela - Monitoramento de Veiculos</p>
                </div>
                <div class="collapse navbar-collapse" id="bs-header-navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="moni.php">Mapa</a></li>
                        <li class="active"><a href="#">Usuários</a></li>
                        <li><a href="#">Dispositivos</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <?php echo htmlspecialchars($email); ?> <i class="caret"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="logout.php"><i class="icon logout"></i> Sair</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="content">
        <div style="height: 60px;"></div>
        <div class="container-fluid">
            <div class="panel panel-default" id="table_clients">
                <div class="panel-heading">
                    <ul class="nav nav-tabs nav-icons pull-right">
                        <li role="presentation">
                            <a href="cadastro_usuario.php" class="action-button" title="Adicionar Usuário">
                                <i class="fa fa-user-plus"></i>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="cadastro_dispositivo.html" class="action-button" title="Adicionar Dispositivo">
                                <i class="fa fa-mobile"></i>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="importar_dispositivos.html" class="action-button" title="Importar Dispositivos">
                                <i class="fa fa-upload"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-list">
                            <thead>
                                <tr>
                                    <th>Ativo</th>
                                    <th>Email</th>
                                    <th>Dispositivos</th>
                                    <th>Limite de dispositivos</th>
                                    <th>Data de validade</th>
                                    <th>Último login</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($usuarios)): ?>
                                    <?php foreach ($usuarios as $usuario): ?>
                                        <tr>
                                            <td><?php echo $usuario['ativo'] ? '<span class="label label-success">Ativo</span>' : '<span class="label label-danger">Inativo</span>'; ?></td>
                                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['dispositivos']); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['limite_dispositivos']); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['data_validade']); ?></td>
                                            <td><?php echo htmlspecialchars($usuario['ultimo_login']); ?></td>
                                            <td>
                                                <a href="editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                                <a href="logar_como.php?id=<?php echo $usuario['id']; ?>" class="btn btn-primary btn-sm">Logar</a>
                                                <a href="excluir_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum usuário encontrado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
