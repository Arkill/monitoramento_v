<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'monitor') {
    header("Location: login.php");
    exit;
}

require 'db_connection.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de usuário inválido.");
}

$id = $_GET['id'];

// Busca os dados do usuário no banco de dados
try {
    $stmt = $pdo->prepare("SELECT id, email, ativo, dispositivos, limite_dispositivos, data_validade FROM usuarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Usuário não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro ao buscar informações do usuário: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Editar Usuário</h1>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Alterações salvas com sucesso!</div>
        <?php endif; ?>
        <form method="POST" action="salvar_usuario.php">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" 
                       value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="ativo">Ativo:</label>
                <select name="ativo" id="ativo" class="form-control">
                    <option value="1" <?php echo $usuario['ativo'] ? 'selected' : ''; ?>>Sim</option>
                    <option value="0" <?php echo !$usuario['ativo'] ? 'selected' : ''; ?>>Não</option>
                </select>
            </div>
            <div class="form-group">
                <label for="dispositivos">Dispositivos:</label>
                <input type="number" name="dispositivos" id="dispositivos" class="form-control" 
                       value="<?php echo htmlspecialchars($usuario['dispositivos']); ?>" min="0" required>
            </div>
            <div class="form-group">
                <label for="limite_dispositivos">Limite de Dispositivos:</label>
                <input type="number" name="limite_dispositivos" id="limite_dispositivos" class="form-control" 
                       value="<?php echo htmlspecialchars($usuario['limite_dispositivos']); ?>" min="0" required>
            </div>
            <div class="form-group">
                <label for="data_validade">Data de Validade:</label>
                <input type="date" name="data_validade" id="data_validade" class="form-control" 
                       value="<?php echo $usuario['data_validade']; ?>" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success">Salvar Alterações</button>
                <a href="monitoring.php" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>
</body>
</html>
