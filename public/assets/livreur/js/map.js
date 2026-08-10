/* PouletExpress Driver Space - Map Integration (Leaflet.js) */

document.addEventListener('DOMContentLoaded', () => {
  initPouletMap();
});

function initPouletMap() {
  const mapContainer = document.getElementById('map');
  if (!mapContainer) return;

  // Coordinates in Côte d'Ivoire (Abidjan region)
  const coordsFarm = [5.4950, -4.0520];    // Ferme Volaille Anyama
  const coordsDriver = [5.4200, -4.0100];  // Driver live position (Boulevard Mitterrand / Autoroute)
  const coordsClient = [5.3500, -3.9700];  // Client Residence Cocody

  // Initialize Leaflet Map
  const map = L.map('map', {
    zoomControl: false,
    attributionControl: false
  }).setView(coordsDriver, 12);

  window.pouletMap = map;

  // Add OpenStreetMap Tile Layer
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  // Custom Icon Factory
  function createCustomIcon(iconName, bgColor) {
    return L.divIcon({
      className: 'custom-div-icon',
      html: `
        <div style="background-color: ${bgColor}; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
          <span class="material-symbols-outlined">${iconName}</span>
        </div>
      `,
      iconSize: [40, 40],
      iconAnchor: [20, 20]
    });
  }

  // Markers
  const farmMarker = L.marker(coordsFarm, { icon: createCustomIcon('agriculture', '#0f5132') }).addTo(map);
  farmMarker.bindPopup(`
    <div style="font-family: sans-serif; padding: 4px;">
      <b style="color: #0f5132;">Ferme Volaille Anyama</b><br/>
      <span>Point de Collecte (120 poulets frais)</span>
    </div>
  `);

  const clientMarker = L.marker(coordsClient, { icon: createCustomIcon('home_pin', '#ea580c') }).addTo(map);
  clientMarker.bindPopup(`
    <div style="font-family: sans-serif; padding: 4px;">
      <b style="color: #ea580c;">Résidence Client - Cocody</b><br/>
      <span>M. KOFFI Emmanuel - 07 08 09 10 11</span>
    </div>
  `);

  const driverMarker = L.marker(coordsDriver, { icon: createCustomIcon('two_wheeler', '#3b82f6') }).addTo(map);
  driverMarker.bindPopup(`
    <div style="font-family: sans-serif; padding: 4px;">
      <b style="color: #3b82f6;">Position Livreur (Jean)</b><br/>
      <span>Vitesse: 42 km/h - En route</span>
    </div>
  `);

  // Route Polyline
  const routePoints = [coordsFarm, coordsDriver, coordsClient];
  const polyline = L.polyline(routePoints, {
    color: '#0f5132',
    weight: 5,
    opacity: 0.8,
    dashArray: '10, 10',
    lineCap: 'round'
  }).addTo(map);

  // Fit view bounds to fit all points
  const group = L.featureGroup([farmMarker, clientMarker, driverMarker]);
  map.fitBounds(group.getBounds().pad(0.2));

  // Global Map Controls
  window.recenterMap = function() {
    map.fitBounds(group.getBounds().pad(0.2));
  };

  window.confirmPickup = function() {
    driverMarker.setLatLng(coordsFarm);
    document.getElementById('mapStatusBadge').textContent = 'EN TRANSIT VERS COCODY';
    document.getElementById('mapStatusBadge').className = 'px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-200';
    if (window.showToast) window.showToast("Enlèvement à la Ferme Anyama validé ! En route pour Cocody.");
  };

  window.confirmDelivery = function() {
    driverMarker.setLatLng(coordsClient);
    document.getElementById('mapStatusBadge').textContent = 'COMMANDE LIVRÉE';
    document.getElementById('mapStatusBadge').className = 'px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-200';
    if (window.showToast) window.showToast("Félicitations ! Commande livrée avec succès.");
  };
}
