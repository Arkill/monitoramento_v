<?php
$host = 'localhost';  // Endereço do servidor de banco de dados
$db = 'monitoramento'; // Nome do banco de dados
$user = 'root';       // Nome de usuário do banco de dados
$pass = '';           // Senha do banco de dados (normalmente vazia no XAMPP)

try {
    // Cria a conexão com o banco de dados usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    // Configurações para tratamento de erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Se ocorrer algum erro, mostra a mensagem de erro
    die("Erro de conexão: " . $e->getMessage());
}
?>

