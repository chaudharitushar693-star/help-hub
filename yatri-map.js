/**
 * YATRI SAHAYATA – Interactive Pilgrim Route & POI Map
 * Uses Leaflet.js and OpenStreetMap
 * Developer: Nidhi Chaudhary (0108CS241082), SATI Vidisha
 */

function initPilgrimMap(containerId = 'yatriMap', circuit = 'Vaishno Devi') {
    const mapEl = document.getElementById(containerId);
    if (!mapEl || typeof L === 'undefined') return;

    // Approximate Coordinates for major pilgrim centers
    const circuitCenters = {
        'Vaishno Devi': { lat: 33.0185, lng: 74.9540, zoom: 13 },
        'Ujjain Mahakal': { lat: 23.1827, lng: 75.7682, zoom: 14 },
        'Char Dham': { lat: 30.7352, lng: 79.0669, zoom: 11 },
        'Omkareshwar': { lat: 22.2464, lng: 76.1511, zoom: 15 },
        'Varanasi Kashi': { lat: 25.3109, lng: 83.0107, zoom: 15 }
    };

    const config = circuitCenters[circuit] || circuitCenters['Vaishno Devi'];

    const map = L.map(containerId).setView([config.lat, config.lng], config.zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors | Yatri Sahayata'
    }).addTo(map);

    // Color-coded icons
    const medicalIcon = L.divIcon({
        className: 'custom-map-icon',
        html: '<div style="background:#ef4444; color:#fff; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 0 6px rgba(0,0,0,0.4); font-size:16px;">🏥</div>',
        iconSize: [30, 30]
    });

    const facilityIcon = L.divIcon({
        className: 'custom-map-icon',
        html: '<div style="background:#0284c7; color:#fff; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 0 6px rgba(0,0,0,0.4); font-size:16px;">ℹ️</div>',
        iconSize: [30, 30]
    });

    const accommodationIcon = L.divIcon({
        className: 'custom-map-icon',
        html: '<div style="background:#f59e0b; color:#fff; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 0 6px rgba(0,0,0,0.4); font-size:16px;">🏨</div>',
        iconSize: [30, 30]
    });

    // Fetch verified POIs from backend API
    fetch(`/api/v1/facilities`)
        .then(res => res.json())
        .then(resp => {
            if (resp.data) {
                resp.data.filter(f => !circuit || f.circuit === circuit).forEach(f => {
                    L.marker([f.latitude, f.longitude], { icon: facilityIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div style="font-family:sans-serif; max-width:200px;">
                                <strong style="color:#0a192f; font-size:13px;">${escapeHtml(f.name)}</strong>
                                <div style="font-size:11px; color:#0284c7; font-weight:bold; margin:2px 0;">${escapeHtml(f.category)}</div>
                                <div style="font-size:11px; color:#64748b;">${escapeHtml(f.location)}</div>
                                <div style="font-size:11px; margin-top:4px;">⏱️ ${escapeHtml(f.operating_hours)}</div>
                            </div>
                        `);
                });
            }
        }).catch(() => {});

    fetch(`/api/v1/medical`)
        .then(res => res.json())
        .then(resp => {
            if (resp.data) {
                resp.data.filter(m => !circuit || m.circuit === circuit).forEach(m => {
                    L.marker([m.latitude, m.longitude], { icon: medicalIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div style="font-family:sans-serif; max-width:200px;">
                                <strong style="color:#b91c1c; font-size:13px;">${escapeHtml(m.name)}</strong>
                                <div style="font-size:11px; color:#ef4444; font-weight:bold; margin:2px 0;">${escapeHtml(m.centre_type)}</div>
                                <div style="font-size:11px; color:#64748b;">${escapeHtml(m.location)}</div>
                                <div style="font-size:11px; margin-top:4px; font-weight:bold;">📞 <a href="tel:${escapeHtml(m.helpline_number)}" style="color:#b91c1c;">${escapeHtml(m.helpline_number)}</a></div>
                            </div>
                        `);
                });
            }
        }).catch(() => {});

    fetch(`/api/v1/accommodations`)
        .then(res => res.json())
        .then(resp => {
            if (resp.data) {
                resp.data.filter(a => !circuit || a.circuit === circuit).forEach(a => {
                    L.marker([a.latitude, a.longitude], { icon: accommodationIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div style="font-family:sans-serif; max-width:200px;">
                                <strong style="color:#0a192f; font-size:13px;">${escapeHtml(a.name)}</strong>
                                <div style="font-size:11px; color:#d97706; font-weight:bold; margin:2px 0;">${escapeHtml(a.type)} (${a.rating}⭐)</div>
                                <div style="font-size:11px; color:#64748b;">${escapeHtml(a.address)}</div>
                                <div style="font-size:11px; margin-top:4px;">💰 ${escapeHtml(a.price_range)}</div>
                            </div>
                        `);
                });
            }
        }).catch(() => {});

    // Draw approximate route line for Vaishno Devi
    if (circuit === 'Vaishno Devi') {
        const routePoints = [
            [32.9928, 74.9318], // Katra Base
            [33.0035, 74.9450], // Banganga
            [33.0120, 74.9490], // Himkoti
            [33.0189, 74.9542], // Ardhkuwari
            [33.0285, 74.9520], // Sanjichhat
            [33.0308, 74.9490], // Bhawan
        ];

        L.polyline(routePoints, {
            color: '#f59e0b',
            weight: 4,
            opacity: 0.85,
            dashArray: '6, 8'
        }).addTo(map).bindPopup('<strong>Approximate Trek Route:</strong> Katra to Holy Bhawan (13.5 km)');
    }

    return map;
}
