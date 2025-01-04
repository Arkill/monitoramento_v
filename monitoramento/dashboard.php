<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'admin') {
    header('Location: login.php'); // Redireciona para o login se não for admin
    exit;
}

// Conexão com o banco de dados
require 'db_connection.php';

// Obtém informações do usuário logado
$stmt = $pdo->prepare("SELECT email FROM usuarios WHERE id = :id");
$stmt->execute(['id' => $_SESSION['usuario_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: logout.php'); // Desloga caso o usuário não seja encontrado
    exit;
}

$email = htmlspecialchars($user['email']);

// Consulta para buscar os clientes ativos
$stmt_ativos = $pdo->prepare("SELECT nome, ultimo_login FROM clientes WHERE status_cliente = 'ativo' ORDER BY ultimo_login DESC");
$stmt_ativos->execute();
$clientes_ativos = $stmt_ativos->fetchAll(PDO::FETCH_ASSOC);

// Consulta para buscar os clientes cancelados
$stmt_cancelados = $pdo->prepare("SELECT nome, data_cancelamento FROM clientes WHERE status_cliente = 'cancelado' ORDER BY data_cancelamento DESC");
$stmt_cancelados->execute();
$clientes_cancelados = $stmt_cancelados->fetchAll(PDO::FETCH_ASSOC);

// Consulta para buscar os clientes VIP
$stmt_vip = $pdo->prepare("SELECT nome, beneficio FROM clientes WHERE status_cliente = 'vip' ORDER BY nome");
$stmt_vip->execute();
$clientes_vip = $stmt_vip->fetchAll(PDO::FETCH_ASSOC);

// Passando os dados para o JavaScript
$dadosClientes = json_encode([
    'ativos' => count($clientes_ativos),
    'cancelados' => count($clientes_cancelados),
    'vip' => count($clientes_vip)
]);

// Dados mockados para os gráficos (você pode substituir com dados reais)
$graficoDados = json_encode([
    'avgSpeed' => [50, 60], // Exemplo de velocidades médias
    'distance' => [120, 150], // Exemplo de distâncias percorridas
    'activity' => [5, 6] // Exemplo de atividades realizadas
]);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Monitoramento de Veículos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.1/MarkerCluster.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.1/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="dashstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Font Awesome para os ícones -->
</head>
<body>
    <div id="container">
        <!-- Cabeçalho -->
        <header class="text-center py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Dashboard de Monitoramento de Veículos</h1>
                <div>
                    <span>Bem-vindo, <strong><?= $email ?></strong></span>
                    <a href="logout.php" class="btn btn-danger btn-sm ml-2">Sair</a>
                </div>
            </div>
        </header>

        <!-- Filtros -->
        <section class="filters my-3 text-center">
            <label for="timeFilter">Período:</label>
            <select id="timeFilter" class="mx-2">
                <option value="today">Hoje</option>
                <option value="week">Última Semana</option>
                <option value="month">Último Mês</option>
            </select>

            <label for="statusFilter">Status:</label>
            <select id="statusFilter" class="mx-2">
                <option value="all">Todos</option>
                <option value="moving">Em Movimento</option>
                <option value="stopped">Parados</option>
                <option value="blocked">Bloqueados</option>
            </select>
        </section>

        <!-- Seção de Clientes -->
        <section class="client-section my-4">
            <div class="row">
                <div class="col-md-4">
                    <h2><i class="fas fa-users"></i> Clientes Ativos</h2>
                    <ul id="activeClients" class="client-list">
                        <?php foreach ($clientes_ativos as $cliente): ?>
                            <li><i class="fas fa-check-circle"></i> <?= $cliente['nome'] ?> - Último uso: <?= $cliente['ultimo_login'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h2><i class="fas fa-ban"></i> Clientes Cancelados</h2>
                    <ul id="cancelledClients" class="client-list">
                        <?php foreach ($clientes_cancelados as $cliente): ?>
                            <li><i class="fas fa-times-circle"></i> <?= $cliente['nome'] ?> - Cancelado em: <?= $cliente['data_cancelamento'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h2><i class="fas fa-crown"></i> Clientes VIP</h2>
                    <ul id="vipClients" class="client-list">
                        <?php foreach ($clientes_vip as $cliente): ?>
                            <li><i class="fas fa-gift"></i> <?= $cliente['nome'] ?> - Benefício: <?= $cliente['beneficio'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Gráficos -->
        <section class="charts my-4 text-center">
            <h2>Estatísticas</h2>
            <div class="row">
                <div class="col-md-4">
                    <canvas id="avgSpeedChart"></canvas>
                </div>
                <div class="col-md-4">
                    <canvas id="distanceChart"></canvas>
                </div>
                <div class="col-md-4">
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Gráfico de controle de clientes -->
        <section class="charts my-4 text-center">
            <h2>Estatísticas de Controle de Clientes</h2>
            <div class="row">
                <div class="col-md-4">
                    <canvas id="clientStatusChart"></canvas>
                </div>
            </div>
        </section>
        <!-- Gráfico de Taxa de Retenção -->
<section class="charts my-4 text-center">
    <h2>Taxa de Retenção de Clientes</h2>
    <div class="row">
        <div class="col-md-4">
            <canvas id="retentionChart"></canvas>
        </div>
    </div>
</section>


        
        <!-- Mapa -->
        <section class="map-section my-4">
            <h2>Localização Atual</h2>
            <div id="maps" style="height: 400px;"></div>
        </section>

        <!-- Feedback -->
        <section class="feedback my-4">
            <h2>Feedback dos Clientes</h2>
            <div class="feedback-item">
                <strong>Ana Souza</strong>
                <p>“O serviço é muito bom, a navegação na interface é intuitiva!”</p>
                <span>⭐⭐⭐⭐⭐</span>
            </div>
            <div class="feedback-item">
                <strong>Lucas Oliveira</strong>
                <p>“Acho que pode melhorar a velocidade do carregamento dos dados.”</p>
                <span>⭐⭐⭐⭐☆</span>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.1/leaflet.markercluster.js"></script>
    <script src="mapdash.js"></script>
    <script>
    // Dados para os gráficos de controle de clientes
    var clientStatusData = <?php echo $dadosClientes; ?>;
    var graficoDados = <?php echo $graficoDados; ?>;

    // Função para criar gráficos
    function createChart(ctx, type, data, labels, label) {
        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: ['#36a2eb', '#ff6384', '#ffcd56'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: label
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Função para carregar os gráficos
    window.onload = function() {
        createChart(
            document.getElementById('clientStatusChart').getContext('2d'),
            'pie',
            [clientStatusData.ativos, clientStatusData.cancelados, clientStatusData.vip],
            ['Ativos', 'Cancelados', 'VIP'],
            'Distribuição dos Clientes'
        );

        createChart(
            document.getElementById('avgSpeedChart').getContext('2d'),
            'line',
            graficoDados.avgSpeed,
            ['Dia 1', 'Dia 2'],
            'Velocidade Média'
        );

        createChart(
            document.getElementById('distanceChart').getContext('2d'),
            'line',
            graficoDados.distance,
            ['Dia 1', 'Dia 2'],
            'Distância Percorrida'
        );

        createChart(
            document.getElementById('activityChart').getContext('2d'),
            'bar',
            graficoDados.activity,
            ['Dia 1', 'Dia 2'],
            'Atividades Realizadas'
        );

        // Dados para o gráfico de Taxa de Retenção
        var retentionData = {
            labels: ['Janeiro', 'Fevereiro', 'Março'], // Exemplo de meses
            data: [80, 75, 85]  // Percentual de retenção (Exemplo)
        };

        // Carregar gráfico de Taxa de Retenção
        var retentionCtx = document.getElementById('retentionChart').getContext('2d');
        createChart(retentionCtx, 'line', retentionData.data, retentionData.labels, 'Taxa de Retenção');
    }
</script>

    <script>
    // Inicializando o mapa em dark mode
    var map = L.map('maps').setView([-23.55052, -46.633308], 12); // São Paulo como exemplo

    // Adicionando camada de mapa em modo escuro
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CartoDB</a>',
        maxZoom: 19
    }).addTo(map);

    // Adicionando marcador de exemplo
    L.marker([-23.55052, -46.633308]).addTo(map)
        .bindPopup('Localização Exemplo: São Paulo.')
        .openPopup();
</script>

</body>
</html>
