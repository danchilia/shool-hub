<style>
.bus-card { border:1px solid #e2e8f0; border-radius:10px; background:#fff; overflow:hidden; }
.bus-card-header { background:#1a5276; color:#fff; padding:12px 16px; }
.bus-badge-online  { background:#d1fae5; color:#065f46; }
.bus-badge-offline { background:#fee2e2; color:#991b1b; }
#map-container { height:500px; border-radius:10px; border:1px solid #e2e8f0; overflow:hidden; }
@media(prefers-color-scheme:dark){ .bus-card { background:#2b2b3a; border-color:#3a3a50; } }
:root[data-theme="dark"]  .bus-card { background:#2b2b3a; border-color:#3a3a50; }
:root[data-theme="light"] .bus-card { background:#fff;     border-color:#e2e8f0; }
</style>

<div class="content-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <h4 class="mb-0"><i class="fas fa-bus me-2 text-primary"></i>GPS Bus Tracking — Live View</h4>
        <div class="d-flex gap-2">
            <a href="<?php echo base_url('bus_tracking/manage'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-cog me-1"></i>Manage Buses
            </a>
            <button class="btn btn-sm btn-success" onclick="refreshPositions()">
                <i class="fas fa-sync me-1"></i>Refresh
            </button>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row g-3">
        <!-- Bus cards -->
        <div class="col-md-3">
            <div class="d-flex flex-column gap-3" id="bus-cards">
                <?php if (empty($buses)): ?>
                <div class="alert alert-info">No buses registered. <a href="<?php echo base_url('bus_tracking/manage'); ?>">Add one</a>.</div>
                <?php else: foreach ($buses as $bus): ?>
                <div class="bus-card" id="bus-card-<?php echo $bus->id; ?>">
                    <div class="bus-card-header d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fw-semibold"><?php echo html_escape($bus->bus_name); ?></div>
                            <div class="opacity-75 small"><?php echo html_escape($bus->reg_number); ?></div>
                        </div>
                        <span class="badge <?php echo $bus->last_location ? 'bus-badge-online' : 'bus-badge-offline'; ?>" id="badge-<?php echo $bus->id; ?>">
                            <?php echo $bus->last_location ? 'Active' : 'Offline'; ?>
                        </span>
                    </div>
                    <div class="p-3">
                        <div class="small"><i class="fas fa-user me-1 text-muted"></i><?php echo html_escape($bus->driver_name ?: '—'); ?></div>
                        <div class="small mt-1"><i class="fas fa-phone me-1 text-muted"></i><?php echo html_escape($bus->driver_phone ?: '—'); ?></div>
                        <?php if ($bus->last_location): ?>
                        <div class="small mt-2 text-muted">
                            Last seen: <?php echo date('H:i', strtotime($bus->last_location->recorded_at)); ?>
                            <?php if ($bus->last_location->speed): ?>
                            &middot; <?php echo round($bus->last_location->speed); ?> km/h
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <button class="btn btn-xs btn-outline-primary mt-2" onclick="centerBus(<?php echo $bus->id; ?>)">
                            <i class="fas fa-crosshairs me-1"></i>Locate
                        </button>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- Map -->
        <div class="col-md-9">
            <div id="map-container">
                <!-- OpenStreetMap via Leaflet — no API key required -->
                <div id="map" style="height:100%;"></div>
            </div>
            <div class="text-muted small mt-2">
                <i class="fas fa-info-circle me-1"></i>
                Map auto-refreshes every 30 seconds.
                GPS device URLs are shown per-bus in <a href="<?php echo base_url('bus_tracking/manage'); ?>">Manage Buses</a> → <i class="fas fa-satellite-dish"></i> GPS Setup button.
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS/JS (open source, no API key) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
var map = L.map('map').setView([-1.286389, 36.817223], 12); // Default: Nairobi
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution:'© <a href="https://openstreetmap.org">OpenStreetMap</a>',
    maxZoom:18
}).addTo(map);

var markers = {};
var busIcon = L.divIcon({ className:'', html:'<div style="background:#1a5276;color:#fff;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:.9rem;box-shadow:0 2px 6px rgba(0,0,0,.4);"><i class="fas fa-bus"></i></div>', iconSize:[32,32], iconAnchor:[16,16] });

function esc(s){ return s ? String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;') : ''; }

function refreshPositions() {
    $.get(base_url+'bus_tracking/live_positions', function(data) {
        data = typeof data==='string' ? JSON.parse(data) : data;
        data.forEach(function(bus) {
            if (!bus.lat || !bus.lng) return;
            var popup = '<strong>'+esc(bus.name)+'</strong><br>'+esc(bus.reg)
                       +(bus.driver?'<br><i class="fas fa-user"></i> '+esc(bus.driver):'')
                       +(bus.driver_phone?'<br><i class="fas fa-phone"></i> '+esc(bus.driver_phone):'')
                       +(bus.speed?'<br>Speed: '+Math.round(bus.speed)+' km/h':'')
                       +(bus.last_seen?'<br><small>Updated: '+esc(bus.last_seen)+'</small>':'');
            if (markers[bus.id]) {
                markers[bus.id].setLatLng([bus.lat, bus.lng]).setPopupContent(popup);
            } else {
                markers[bus.id] = L.marker([bus.lat, bus.lng], {icon:busIcon})
                    .addTo(map).bindPopup(popup);
            }
            var badge = document.getElementById('badge-'+bus.id);
            if (badge) { badge.className='badge bus-badge-online'; badge.textContent='Active'; }
        });
    });
}

function centerBus(id) {
    if (markers[id]) { map.setView(markers[id].getLatLng(), 15); markers[id].openPopup(); }
    else { alert('No location data for this bus yet.'); }
}

refreshPositions();
setInterval(refreshPositions, 30000);
</script>
