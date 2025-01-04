// Inicialização do mapa centralizado em Manaus com o modo escuro
const map = L.map('maps').setView([-3.119027, -60.021731], 12); // Mudança aqui, de 'map' para 'maps'

// Adicionando o tile layer do modo escuro (CartoDB Dark Matter)
L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
  attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://carto.com/attributions">CartoDB</a>',
}).addTo(map);

// Criando um grupo de marcadores para o agrupamento
const vehicleClusterGroup = L.markerClusterGroup();

// Função para criar e mover veículos
function createVehicle({ iconUrl, startCoords, endCoords, vehicleId, vehicleName, vehicleBrand, vehiclePlate }) {
  const vehicleIcon = new L.Icon({
    iconUrl,
    iconSize: [60, 40],
    iconAnchor: [30, 40],
  });

  const vehicleMarker = L.marker(startCoords, { icon: vehicleIcon });

  // Adicionando o marcador ao grupo de agrupamento
  vehicleClusterGroup.addLayer(vehicleMarker);

  vehicleMarker.bindPopup(`
    <b>Marca:</b> ${vehicleBrand}<br>
    <b>Nome:</b> ${vehicleName}<br>
    <b>Placa:</b> ${vehiclePlate}<br>
    <b>Velocidade:</b> 0 km/h
  `);

  const routeLine = L.polyline([], { color: 'blue', weight: 4 }).addTo(map);

  L.Routing.control({
    waypoints: [L.latLng(startCoords), L.latLng(endCoords)],
    createMarker: () => null,
    addWaypoints: false,
    routeWhileDragging: false,
    draggableWaypoints: false,
    fitSelectedRoutes: false,
    show: false,
  }).on('routesfound', (e) => {
    const routeCoordinates = e.routes[0].coordinates;
    moveVehicleOnRoute(vehicleMarker, routeLine, routeCoordinates, vehicleId);
  }).addTo(map);
}

// Função para mover o veículo ao longo da rota
function moveVehicleOnRoute(marker, routeLine, routeCoordinates, vehicleId) {
  let index = 0;

  function move() {
    if (index < routeCoordinates.length) {
      const coords = routeCoordinates[index];
      marker.setLatLng(coords);
      routeLine.addLatLng(coords);

      // Simula variação na velocidade
      const speed = Math.floor(Math.random() * 40 + 20);  // Velocidade entre 20 e 60 km/h
      marker.setPopupContent(`
        <b>Velocidade:</b> ${speed} km/h
      `);

      index++;
      setTimeout(move, 1000);  // Movimento do veículo a cada 1 segundo
    }
  }

  move();
}

// Criar veículos e adicioná-los ao grupo de marcadores
createVehicle({
  iconUrl: 'img/r15.png',
  startCoords: [-3.098264, -59.986498],
  endCoords: [-3.119027, -60.021731],
  vehicleId: 'moto-info',
  vehicleName: 'Yamaha R15',
  vehicleBrand: 'Yamaha',
  vehiclePlate: 'ABC-1234',
});

createVehicle({
  iconUrl: 'img/Celta.png', // Caminho do ícone do veículo
  startCoords: [-3.119027, -60.021731], // Coordenadas iniciais do Celta
  endCoords: [-3.072237, -60.017906], // Coordenadas finais do Celta
  vehicleId: 'celta-info', // ID único para o Celta
  vehicleName: 'Chevrolet Celta', // Nome do veículo
  vehicleBrand: 'Chevrolet', // Marca do veículo
  vehiclePlate: 'XYZ-5678', // Placa do veículo
});

// Adicionar o grupo de veículos agrupados ao mapa
map.addLayer(vehicleClusterGroup);
