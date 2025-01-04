<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentinela - Monitoramento de Veículos em Manaus</title>

    <!-- Adicionando o CSS do Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="img/R.png">
    <link rel="stylesheet" href="mcss.css">
    <link rel="stylesheet" href="moni.css">
</head>
<body>
    <header>
        <h1>Sentinela - Monitoramento de Veículos em Manaus</h1>
        <nav>
            <ul>
                <li><a id="admin-button" href="monitoring.php"><i class="fas fa-user-cog"></i> Admin</a></li>
                <li><a id="dashboard-button" href="dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            </ul>
        </nav>        
    </header>
    <div id="map"></div>
    <div id="sidebar-handle"></div>
    <div id="sidebar">
        <h2>Informações dos Veículos</h2>
        
        <!-- Botão de colapso -->
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <i class="fas fa-chevron-down"></i> Exibir Informações
        </button>
        
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <div id="vehicle-list">
                    <!-- As informações dos veículos serão inseridas aqui dinamicamente -->
                </div>
            </div>
        </div>  
    </div>
    <div id="additional-sensor-bar">
    <div class="additional-sensor-info">
        <h3>Carro Monitorado</h3>
        <p><i class="fas fa-clock"></i> Horário: 14:30</p>
        <p><i class="fas fa-hourglass-half"></i> Duração da Parada: <span id="car-stop-duration">0 min</span></p>
        <p><i class="fas fa-user"></i> Condutor: João Silva</p>
        <p><i class="fas fa-key"></i> Ignição: <span id="car-ignition">Ligada</span></p>
        <p><i class="fas fa-lock"></i> Bloqueio: Desativado</p>
    </div>
    <div class="additional-sensor-info">
        <h3>Moto Monitorada</h3>
        <p><i class="fas fa-clock"></i> Horário: 15:00</p>
        <p><i class="fas fa-hourglass-half"></i> Duração da Parada: <span id="bike-stop-duration">0 min</span></p>
        <p><i class="fas fa-user"></i> Condutor: Maria Souza</p>
        <p><i class="fas fa-key"></i> Ignição: <span id="bike-ignition">Ligada</span></p>
        <p><i class="fas fa-lock"></i> Bloqueio: Ativado</p>
    </div>              
</div>

    <!-- Script do Leaflet -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <!-- Vinculação do arquivo JavaScript -->
    <script src="monitoramento.js"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
