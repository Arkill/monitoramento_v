// Inicialização do mapa e do grupo de clusters
var map = L.map('map').setView([-3.119027, -60.021731], 12); // Centraliza o mapa em Manaus, por exemplo

// Adicionando o tile layer (mapa base)
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
}).addTo(map);

// Criar um grupo de clusters
var markers = L.markerClusterGroup();

// Função para adicionar um marcador de veículo no mapa
function addVehicleMarker(lat, lon, status, vehicleId, vehicleType) {
    var iconUrl = 'car-icon.png'; // Padrão

    // Alterar o ícone dependendo do status do veículo
    if (status === 'moving') {
        iconUrl = 'moving-icon.png'; // Ícone para veículos em movimento
    } else if (status === 'stopped') {
        iconUrl = 'stopped-icon.png'; // Ícone para veículos parados
    } else if (status === 'blocked') {
        iconUrl = 'blocked-icon.png'; // Ícone para veículos bloqueados
    }

    // Verifique o tipo do veículo e use o ícone correto (R15 ou Celta)
    if (vehicleType === 'R15') {
        iconUrl = 'img/r15.png'; // Caminho para o ícone do R15
    } else if (vehicleType === 'Celta') {
        iconUrl = 'img/celta.png'; // Caminho para o ícone do Celta
    }

    var vehicleIcon = L.icon({
        iconUrl: iconUrl,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });

    var marker = L.marker([lat, lon], { icon: vehicleIcon }).bindPopup("Veículo ID: " + vehicleId);

    markers.addLayer(marker); // Adiciona o marcador ao grupo de clusters
}

// Função para adicionar o caminho percorrido de um veículo
function addVehicleRoute(routeData) {
    var latlngs = routeData.map(point => [point.lat, point.lon]);
    var polyline = L.polyline(latlngs, { color: 'blue' }).addTo(map);
    polyline.bindPopup("Caminho percorrido pelo veículo");
}

// Exemplo de dados fictícios para veículos com tipo R15 e Celta
var vehicles = [
    { lat: -3.119027, lon: -60.021731, status: 'moving', vehicleId: 1, vehicleType: 'R15' },
    { lat: -3.118500, lon: -60.022500, status: 'stopped', vehicleId: 2, vehicleType: 'Celta' },
    { lat: -3.120000, lon: -60.020000, status: 'blocked', vehicleId: 3, vehicleType: 'R15' },
];

// Adicionar os marcadores de veículos com tipos específicos
vehicles.forEach(vehicle => {
    addVehicleMarker(vehicle.lat, vehicle.lon, vehicle.status, vehicle.vehicleId, vehicle.vehicleType);
});

// Exemplo de dados para o caminho percorrido de um veículo
var routeData = [
    { lat: -3.119027, lon: -60.021731 },
    { lat: -3.119500, lon: -60.022200 },
    { lat: -3.120000, lon: -60.023000 },
];

// Adicionar o caminho percorrido de um veículo
addVehicleRoute(routeData);

// Adicionar o grupo de clusters ao mapa
map.addLayer(markers);

// Variáveis globais para instâncias dos gráficos
let avgSpeedChartInstance = null;
let distanceChartInstance = null;
let activityChartInstance = null;
let fuelConsumptionChartInstance = null;
let engineHealthChartInstance = null;
let tripCountChartInstance = null;
let clientStatusChartInstance = null;
let clientGrowthChartInstance = null;

// Dados fictícios para os gráficos de veículos
const avgSpeedChartData = {
    labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
    datasets: [{
        label: 'Velocidade Média (km/h)',
        data: [45, 50, 55, 60, 58, 62, 65],
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderWidth: 1
    }]
};

const distanceChartData = {
    labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
    datasets: [{
        label: 'Distância (km)',
        data: [120, 150, 170, 160, 140, 180, 190],
        borderColor: 'rgba(153, 102, 255, 1)',
        backgroundColor: 'rgba(153, 102, 255, 0.2)',
        borderWidth: 1
    }]
};

const activityChartData = {
    labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
    datasets: [{
        label: 'Atividade (%)',
        data: [80, 75, 85, 90, 88, 95, 100],
        borderColor: 'rgba(255, 159, 64, 1)',
        backgroundColor: 'rgba(255, 159, 64, 0.2)',
        borderWidth: 1
    }]
};

const fuelConsumptionChartData = {
    labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
    datasets: [{
        label: 'Consumo de Combustível (litros)',
        data: [12, 14, 15, 13, 11, 16, 17],
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderWidth: 1
    }]
};

const engineHealthChartData = {
    labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
    datasets: [{
        label: 'Saúde do Motor (%)',
        data: [90, 88, 85, 87, 89, 86, 84],
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderWidth: 1
    }]
};

const tripCountChartData = {
    labels: ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
    datasets: [{
        label: 'Quantidade de Viagens',
        data: [10, 12, 14, 13, 11, 15, 16],
        borderColor: 'rgba(255, 206, 86, 1)',
        backgroundColor: 'rgba(255, 206, 86, 0.2)',
        borderWidth: 1
    }]
};

// Dados fictícios para os gráficos de controle de clientes
const clientStatusData = {
    labels: ['Ativos', 'Cancelados', 'VIPs'],
    datasets: [{
        label: 'Quantidade de Clientes',
        data: [120, 30, 15], // Quantidade fictícia de clientes em cada categoria
        backgroundColor: [
            'rgba(75, 192, 192, 0.6)', // Cor para clientes ativos
            'rgba(255, 99, 132, 0.6)', // Cor para clientes cancelados
            'rgba(255, 206, 86, 0.6)'  // Cor para clientes VIPs
        ],
        borderColor: [
            'rgba(75, 192, 192, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(255, 206, 86, 1)'
        ],
        borderWidth: 1
    }]
};

const clientGrowthData = {
    labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho'],
    datasets: [{
        label: 'Novos Clientes',
        data: [20, 25, 30, 40, 35, 50],
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderWidth: 2
    }]
};

// Função para inicializar ou recriar um gráfico
function createChart(ctx, chartInstance, chartData, chartType) {
    if (chartInstance) {
        chartInstance.destroy(); // Destroi o gráfico existente
    }
    return new Chart(ctx, {
        type: chartType,
        data: chartData,
        options: { responsive: true }
    });
}

// Inicialização dos gráficos
window.onload = function () {
    // Gráficos de veículos
    const ctx1 = document.getElementById('avgSpeedChart').getContext('2d');
    avgSpeedChartInstance = createChart(ctx1, avgSpeedChartInstance, avgSpeedChartData, 'line');

    const ctx2 = document.getElementById('distanceChart').getContext('2d');
    distanceChartInstance = createChart(ctx2, distanceChartInstance, distanceChartData, 'line');

    const ctx3 = document.getElementById('activityChart').getContext('2d');
    activityChartInstance = createChart(ctx3, activityChartInstance, activityChartData, 'line');

    const ctx4 = document.getElementById('fuelConsumptionChart').getContext('2d');
    fuelConsumptionChartInstance = createChart(ctx4, fuelConsumptionChartInstance, fuelConsumptionChartData, 'bar');

    const ctx5 = document.getElementById('engineHealthChart').getContext('2d');
    engineHealthChartInstance = createChart(ctx5, engineHealthChartInstance, engineHealthChartData, 'radar');

    const ctx6 = document.getElementById('tripCountChart').getContext('2d');
    tripCountChartInstance = createChart(ctx6, tripCountChartInstance, tripCountChartData, 'bar');

    // Gráficos de controle de clientes
    const ctx7 = document.getElementById('clientStatusChart').getContext('2d');
    clientStatusChartInstance = createChart(ctx7, clientStatusChartInstance, clientStatusData, 'doughnut');

    const ctx8 = document.getElementById('clientGrowthChart').getContext('2d');
    clientGrowthChartInstance = createChart(ctx8, clientGrowthChartInstance, clientGrowthData, 'line');
};
