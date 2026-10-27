(() => {
  const $ = (id) => document.getElementById(id);
  const map = L.map('map').setView([30.0444, 31.2357], 11);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);
  let routeLayer = null;
  let markers = [];

  function message(text, kind = 'info') {
    const el = $('routeMessage');
    el.textContent = text;
    el.className = `notice ${kind}`;
  }
  function clearMessage() {
    $('routeMessage').textContent = '';
    $('routeMessage').className = 'notice hidden';
  }
  function cleanMap() {
    if (routeLayer) { map.removeLayer(routeLayer); routeLayer = null; }
    markers.forEach(m => map.removeLayer(m)); markers = [];
  }
  async function geocode(place) {
    const url = 'https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=' + encodeURIComponent(place);
    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('Location search service is unavailable. Try again in a moment.');
    const rows = await res.json();
    if (!rows.length) throw new Error(`Could not find "${place}". Try a more specific address.`);
    return { lat: Number(rows[0].lat), lng: Number(rows[0].lon), label: rows[0].display_name };
  }
  async function getRoute(a, b) {
    const url = `https://router.project-osrm.org/route/v1/driving/${a.lng},${a.lat};${b.lng},${b.lat}?overview=full&geometries=geojson&steps=false`;
    const res = await fetch(url);
    if (!res.ok) throw new Error('Routing service is unavailable. Please try again.');
    const data = await res.json();
    if (data.code !== 'Ok' || !data.routes?.length) throw new Error('No driving route was found between these locations.');
    return data.routes[0];
  }
  $('incidentDestination').addEventListener('change', () => {
    if (!$('incidentDestination').value) return;
    try {
      const i = JSON.parse($('incidentDestination').value);
      $('destination').value = `${i.title} — ${i.location}`;
      $('destination').dataset.lat = i.lat;
      $('destination').dataset.lng = i.lng;
    } catch (_) { /* Ignore malformed options. */ }
  });
  $('destination').addEventListener('input', () => {
    delete $('destination').dataset.lat; delete $('destination').dataset.lng;
    $('incidentDestination').value = '';
  });
  $('swapButton').addEventListener('click', () => {
    const a = $('origin').value; $('origin').value = $('destination').value; $('destination').value = a;
    delete $('destination').dataset.lat; delete $('destination').dataset.lng; $('incidentDestination').value = '';
  });
  $('routeForm').addEventListener('submit', async (event) => {
    event.preventDefault(); clearMessage();
    const button = $('routeButton'); button.disabled = true; button.textContent = 'Calculating…';
    $('routeResult').classList.add('hidden'); $('routeSaveStatus').textContent = 'Calculating route…';
    try {
      const originName = $('origin').value.trim(); const destName = $('destination').value.trim();
      if (!originName || !destName) throw new Error('Enter both a starting location and a destination.');
      const a = await geocode(originName);
      let b;
      if ($('destination').dataset.lat && $('destination').dataset.lng) {
        b = { lat: Number($('destination').dataset.lat), lng: Number($('destination').dataset.lng), label: destName };
      } else { b = await geocode(destName); }
      const route = await getRoute(a, b);
      cleanMap();
      routeLayer = L.geoJSON(route.geometry, { style: { color: '#1769d2', weight: 6, opacity: .9 } }).addTo(map);
      markers.push(L.marker([a.lat,a.lng]).addTo(map).bindPopup('Start: ' + originName));
      markers.push(L.marker([b.lat,b.lng]).addTo(map).bindPopup('Destination: ' + destName));
      map.fitBounds(routeLayer.getBounds(), { padding: [30,30] });
      const km = route.distance / 1000; const minutes = route.duration / 60;
      $('distanceValue').textContent = `${km.toFixed(1)} km`;
      $('durationValue').textContent = minutes < 60 ? `${Math.round(minutes)} min` : `${Math.floor(minutes / 60)} h ${Math.round(minutes % 60)} min`;
      $('routeResult').classList.remove('hidden');
      message('Route found successfully.', 'success');
      try {
        const saved = await fetch('save_route.php', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({origin_name:originName,destination_name:destName,origin_lat:a.lat,origin_lng:a.lng,destination_lat:b.lat,destination_lng:b.lng,distance_km:km,duration_minutes:minutes}) });
        const savedResult = await saved.json();
        $('routeSaveStatus').textContent = savedResult.ok ? 'Route search saved to MySQL.' : 'Route displayed; history was not saved.';
      } catch (_) { $('routeSaveStatus').textContent = 'Route displayed; history could not be saved.'; }
    } catch (err) { message(err.message || 'Could not calculate the route.', 'error'); $('routeSaveStatus').textContent = 'Route not saved.'; }
    finally { button.disabled = false; button.textContent = 'Find best route'; }
  });
})();
