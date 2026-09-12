<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.region-card {
    max-width: 560px;
    margin: 40px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    overflow: hidden;
}
.region-card-header {
    background: linear-gradient(135deg, #1a5276, #2e86c1);
    color: #fff;
    padding: 28px 32px 20px;
    text-align: center;
}
.region-card-header h3 { margin: 0 0 6px; font-size: 20px; }
.region-card-header p  { margin: 0; font-size: 13px; opacity: 0.85; }
.region-card-body { padding: 28px 32px; }

#step-detecting { text-align: center; padding: 20px 0; }
#step-detecting .spinner {
    width: 48px; height: 48px;
    border: 5px solid #d5e8f7;
    border-top-color: #2e86c1;
    border-radius: 50%;
    animation: spin 0.9s linear infinite;
    margin: 0 auto 16px;
}
@keyframes spin { to { transform: rotate(360deg); } }

#step-confirm { display: none; }
.detected-box {
    background: #eaf4fb;
    border: 1px solid #aed6f1;
    border-radius: 8px;
    padding: 18px 22px;
    margin-bottom: 20px;
}
.detected-box .label-sm { font-size: 11px; color: #5d7d8c; text-transform: uppercase; letter-spacing: .5px; }
.detected-box .county-name { font-size: 22px; font-weight: 700; color: #1a5276; margin: 2px 0 6px; }
.detected-box .subcounty-name { font-size: 16px; color: #2e86c1; font-weight: 600; }

#step-manual { display: none; }
.form-group label { font-weight: 600; color: #333; margin-bottom: 4px; display: block; }
.form-control { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; }
.btn-confirm {
    background: #27ae60; color: #fff; border: none;
    padding: 12px 28px; border-radius: 6px; font-size: 15px;
    font-weight: 600; cursor: pointer; width: 100%; margin-top: 8px;
}
.btn-change {
    background: transparent; color: #2e86c1; border: 1px solid #2e86c1;
    padding: 10px 20px; border-radius: 6px; font-size: 13px;
    cursor: pointer; width: 100%; margin-top: 8px;
}
.btn-confirm:hover { background: #1e8449; }
.btn-change:hover  { background: #eaf4fb; }
.alert-danger { background: #fdecea; border: 1px solid #e74c3c; color: #922b21; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; }
.gps-error { color: #e74c3c; font-size: 13px; margin-bottom: 12px; }
</style>

<div class="region-card">
    <div class="region-card-header">
        <i class="fas fa-map-marker-alt" style="font-size:32px;margin-bottom:10px;display:block;opacity:.9;"></i>
        <h3>Set Your Working Region</h3>
        <p>We need to know which area you will be visiting schools in</p>
    </div>
    <div class="region-card-body">

        <?php if (!empty($error)): ?>
        <div class="alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Step 1: Detecting -->
        <div id="step-detecting">
            <div class="spinner"></div>
            <p style="color:#555;font-size:14px;">Detecting your current location…</p>
            <p style="color:#999;font-size:12px;">Please allow location access when your browser asks</p>
        </div>

        <!-- Step 2: Confirm detected location -->
        <div id="step-confirm">
            <p style="color:#333;font-size:14px;margin-bottom:14px;">
                <i class="fas fa-check-circle" style="color:#27ae60;"></i>
                We detected your current location:
            </p>
            <div class="detected-box">
                <div class="label-sm">County</div>
                <div class="county-name" id="detected-county">—</div>
                <div class="label-sm" style="margin-top:10px;">Sub-County</div>
                <div class="subcounty-name" id="detected-subcounty">—</div>
            </div>
            <p style="font-size:13px;color:#555;margin-bottom:16px;">
                Will this be your working field — the area where you will visit schools?
            </p>
            <?php echo form_open('agent_portal/save_region'); ?>
                <input type="hidden" name="county"     id="confirm-county">
                <input type="hidden" name="sub_county" id="confirm-subcounty">
                <button type="submit" class="btn-confirm">
                    <i class="fas fa-check"></i> Yes, this is my area
                </button>
            <?php echo form_close(); ?>
            <button class="btn-change" onclick="showManual()">
                <i class="fas fa-edit"></i> No, let me choose my area
            </button>
        </div>

        <!-- Step 3: Manual select (fallback) -->
        <div id="step-manual">
            <p id="gps-error-msg" class="gps-error" style="display:none;"></p>
            <p style="color:#333;font-size:14px;margin-bottom:16px;">
                <i class="fas fa-map-marked-alt" style="color:#2e86c1;"></i>
                Select the county and sub-county where you will be working:
            </p>
            <?php echo form_open('agent_portal/save_region'); ?>
                <div class="form-group" style="margin-bottom:16px;">
                    <label>County <span style="color:#e74c3c;">*</span></label>
                    <select name="county" id="manual-county" class="form-control" onchange="loadSubCounties(this.value)" required>
                        <option value="">— Select County —</option>
                        <?php foreach (array_keys($kenya_regions) as $county): ?>
                        <option value="<?php echo html_escape($county); ?>"><?php echo html_escape($county); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:20px;">
                    <label>Sub-County <span style="color:#e74c3c;">*</span></label>
                    <select name="sub_county" id="manual-subcounty" class="form-control" required>
                        <option value="">— Select County First —</option>
                    </select>
                </div>
                <button type="submit" class="btn-confirm">
                    <i class="fas fa-save"></i> Save My Region
                </button>
            <?php echo form_close(); ?>
        </div>

    </div>
</div>

<script>
var kenyaRegions = <?php echo json_encode($kenya_regions); ?>;

// Normalize Nominatim county name to match our list
function normalizeCounty(raw) {
    if (!raw) return '';
    var cleaned = raw.replace(/\s*(City\s*)?County\s*$/i, '').trim();
    // Try exact match first
    if (kenyaRegions[cleaned]) return cleaned;
    // Try case-insensitive
    var keys = Object.keys(kenyaRegions);
    for (var i = 0; i < keys.length; i++) {
        if (keys[i].toLowerCase() === cleaned.toLowerCase()) return keys[i];
    }
    // Try partial match
    for (var i = 0; i < keys.length; i++) {
        if (keys[i].toLowerCase().indexOf(cleaned.toLowerCase()) !== -1 ||
            cleaned.toLowerCase().indexOf(keys[i].toLowerCase()) !== -1) return keys[i];
    }
    return cleaned;
}

// Find best matching sub-county from Nominatim fields
function matchSubCounty(county, nominatimAddr) {
    if (!kenyaRegions[county]) return '';
    var subList = kenyaRegions[county];
    var candidates = [
        nominatimAddr.suburb,
        nominatimAddr.city_district,
        nominatimAddr.state_district,
        nominatimAddr.town,
        nominatimAddr.village,
        nominatimAddr.neighbourhood
    ];
    for (var c = 0; c < candidates.length; c++) {
        if (!candidates[c]) continue;
        var cand = candidates[c].toLowerCase().trim();
        for (var s = 0; s < subList.length; s++) {
            if (subList[s].toLowerCase() === cand) return subList[s];
            if (subList[s].toLowerCase().indexOf(cand) !== -1 ||
                cand.indexOf(subList[s].toLowerCase()) !== -1) return subList[s];
        }
    }
    return '';
}

function showConfirm(county, subCounty) {
    document.getElementById('step-detecting').style.display = 'none';
    document.getElementById('detected-county').textContent    = county + ' County';
    document.getElementById('detected-subcounty').textContent = subCounty ? subCounty + ' Sub-County' : 'Sub-county not detected — please verify';
    document.getElementById('confirm-county').value     = county;
    document.getElementById('confirm-subcounty').value  = subCounty;
    document.getElementById('step-confirm').style.display = 'block';

    // If sub-county wasn't matched, pre-fill the manual dropdowns and suggest changing
    if (!subCounty) {
        preSelectCounty(county);
    }
}

function showManual(preCounty) {
    document.getElementById('step-detecting').style.display = 'none';
    document.getElementById('step-confirm').style.display   = 'none';
    document.getElementById('step-manual').style.display    = 'block';
    if (preCounty) preSelectCounty(preCounty);
}

function preSelectCounty(county) {
    var sel = document.getElementById('manual-county');
    for (var i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value === county) {
            sel.selectedIndex = i;
            loadSubCounties(county);
            break;
        }
    }
}

function loadSubCounties(county) {
    var sel = document.getElementById('manual-subcounty');
    sel.innerHTML = '';
    if (!county || !kenyaRegions[county]) {
        sel.innerHTML = '<option value="">— Select County First —</option>';
        return;
    }
    var subs = kenyaRegions[county];
    var opt = document.createElement('option');
    opt.value = ''; opt.textContent = '— Select Sub-County —';
    sel.appendChild(opt);
    for (var i = 0; i < subs.length; i++) {
        var o = document.createElement('option');
        o.value = subs[i]; o.textContent = subs[i];
        sel.appendChild(o);
    }
}

// Start GPS detection
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            var lat = pos.coords.latitude;
            var lng = pos.coords.longitude;

            fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng, {
                headers: { 'Accept-Language': 'en', 'User-Agent': 'CSTSchoolHub/1.0' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var addr = data.address || {};
                var county    = normalizeCounty(addr.county || addr.state || '');
                var subCounty = county ? matchSubCounty(county, addr) : '';
                if (county) {
                    showConfirm(county, subCounty);
                } else {
                    showManual('');
                }
            })
            .catch(function() {
                showManual('');
            });
        },
        function(err) {
            document.getElementById('step-detecting').style.display = 'none';
            document.getElementById('step-manual').style.display    = 'block';
            var msg = 'Location access was denied. Please select your region manually.';
            if (err.code === 2) msg = 'Location could not be detected. Please select your region manually.';
            if (err.code === 3) msg = 'Location detection timed out. Please select your region manually.';
            var el = document.getElementById('gps-error-msg');
            el.textContent = msg;
            el.style.display = 'block';
        },
        { timeout: 10000, maximumAge: 60000 }
    );
} else {
    showManual('');
}
</script>
