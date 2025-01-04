// Dados para os gráficos
var vehicleData = {
    labels: ['Moto R15', 'Carro Celta'],
    avgSpeed: [50, 60],  // Velocidade média dos veículos
    distance: [120, 150],  // Distância percorrida pelos veículos
    activity: [5, 6]  // Atividades realizadas pelos veículos
};

// Função para criar gráficos
function createChart(ctx, type, data, labels, label) {
    new Chart(ctx, {
        type: type,
        data: {
            labels: labels,
            datasets: [
                {
                    label: label,
                    data: data,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',  // Cor para o primeiro veículo
                        'rgba(255, 99, 132, 0.2)'   // Cor para o segundo veículo
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',    // Cor da borda para o primeiro veículo
                        'rgba(255, 99, 132, 1)'     // Cor da borda para o segundo veículo
                    ],
                    borderWidth: 1
                }
            ]
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

// Criação dos gráficos
document.addEventListener('DOMContentLoaded', function () {
    var avgSpeedCtx = document.getElementById('avgSpeedChart').getContext('2d');
    createChart(avgSpeedCtx, 'bar', vehicleData.avgSpeed, vehicleData.labels, 'Velocidade Média');

    var distanceCtx = document.getElementById('distanceChart').getContext('2d');
    createChart(distanceCtx, 'line', vehicleData.distance, vehicleData.labels, 'Distância');

    var activityCtx = document.getElementById('activityChart').getContext('2d');
    createChart(activityCtx, 'pie', vehicleData.activity, vehicleData.labels, 'Atividade');
});
