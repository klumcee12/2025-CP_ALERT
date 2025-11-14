<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALERT+ Parent Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('ccs/dashboard.css') }}">
    <!-- Google Maps API -->
    @php
        $mapsApiKey = config('services.google.maps_api_key');
    @endphp
    @if($mapsApiKey)
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $mapsApiKey }}&libraries=geometry"></script>
    @else
        <script>
            console.error('Google Maps API key is not configured. Please set GOOGLE_MAPS_API_KEY in your .env file.');
        </script>
    @endif
  </head>
  <body>
    <div class="app" id="app">
      <header>
        <div class="brand">
          ALERT<span style="margin-left: 4px">+</span>
          <sup>Parent Dashboard</sup>
        </div>
        <div
          class="banner success"
          id="topBanner"
          role="status"
          aria-live="polite"
        >
          Ready — GSM/SMS mode
        </div>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
          ☰
        </button>
      </header>

      <aside id="sidebar" aria-label="Main">
        <button class="nav-btn" data-tab="location" aria-current="page">
          📍 Location
        </button>
        <button class="nav-btn" data-tab="users">👨‍👩‍👧 Users</button>
        <button class="nav-btn" data-tab="call">🔔 Presence Call</button>
        <button class="nav-btn" data-tab="settings">⚙️ Settings</button>
        <div style="margin-top: auto"></div>
        <form id="logoutForm" method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="nav-btn danger" type="submit">🚪 Logout</button>
        </form>
      </aside>

      <main>
        <!-- LOCATION PAGE -->
        <section class="page active" id="page-location">
          <div class="row cols-2">
            <div class="card">
              <h3 class="title">Select User</h3>
              <div class="toolbar">
                <select id="userSelect" aria-label="Select child"></select>
                <button class="btn" id="btnPing">Ping Location</button>
                <button class="btn secondary" id="btnRefresh">Refresh</button>
                <button class="btn ghost" id="btnExport">Export CSV</button>
              </div>
              <div class="statusbar" id="statusbar"></div>
              <div class="card" style="margin-top: 12px">
                <h4 class="title" style="margin-bottom: 6px">Location Log</h4>
                <div class="toolbar">
                  <select id="filterRange" aria-label="Filter by range">
                    <option value="all">All</option>
                    <option value="today">Today</option>
                    <option value="7">Last 7 days</option>
                  </select>
                  <select id="filterStatus" aria-label="Filter by status">
                    <option value="all">All</option>
                    <option value="success">Success</option>
                    <option value="fail">Failed</option>
                  </select>
                </div>
                <div class="list" id="logList">
                  <table class="timeline" aria-describedby="Location timeline">
                    <thead>
                      <tr>
                        <th>When</th>
                        <th>Who</th>
                        <th>Address</th>
                        <th>Source</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody id="logBody"></tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="card">
              <h3 class="title">Map</h3>
              <div class="map" id="map">
                <div class="coords" id="coords">—</div>
              </div>
              <div class="toolbar" style="margin-top: 12px">
                <button class="btn ghost" id="btnOpenMaps">
                  Open in Google Maps
                </button>
                <button class="btn ghost" id="btnCenter">
                  Center on Child
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- USERS PAGE -->
        <section class="page" id="page-users">
          <div class="row cols-2 users-layout">
            <div class="card">
              <div class="title-row">
                <h3 class="title">Family & Devices</h3>
                <div class="chip badge" id="dependentTotalBadge">0 dependents</div>
              </div>
              <div class="toolbar">
                <button class="btn" id="btnAddChild">Register Device</button>
              </div>
              <div class="list" id="userCards"></div>
            </div>
            <div class="card profile-card">
              <h3 class="title">Dependent Profile</h3>
              <div class="dependent-stats">
                <div>
                  <span>Total dependents</span>
                  <strong id="dependentCount">0</strong>
                </div>
                <div>
                  <span>Registered devices</span>
                  <strong id="deviceCount">0</strong>
                </div>
              </div>
              <div class="profile-header">
                <div class="profile-avatar" id="profileAvatar">?</div>
                <div>
                  <div class="profile-name" id="profileName">Select a dependent</div>
                  <div class="profile-meta" id="profileMeta">
                    Choose someone from the list to view their details.
                  </div>
                </div>
              </div>
              <div class="profile-grid">
                <div>
                  <span>Device ID</span>
                  <strong id="profileDevice">—</strong>
                </div>
                <div>
                  <span>SIM Number</span>
                  <strong id="profileSim">—</strong>
                </div>
              </div>
              <div class="profile-grid">
                <div>
                  <span>Category</span>
                  <strong id="profileCategory">—</strong>
                </div>
                <div>
                  <span>Last Seen</span>
                  <strong id="profileLastSeen">—</strong>
                </div>
              </div>
              <div class="profile-grid">
                <div>
                  <span>Signal</span>
                  <strong id="profileSignal">—</strong>
                </div>
                <div>
                  <span>Battery</span>
                  <strong id="profileBattery">—</strong>
                </div>
              </div>
              <div class="profile-grid">
                <div>
                  <span>Coordinates</span>
                  <strong id="profileCoords">—</strong>
                </div>
              </div>
              <p class="profile-note" id="profileNote">
                Keep devices synced to get live battery, signal, and last location updates here.
              </p>
              <div class="toolbar">
                <button class="btn secondary" type="button" id="btnProfilePing" disabled>
                  Quick Ping
                </button>
                <button class="btn ghost" type="button" id="btnProfileLocate" disabled>
                  Center on Map
                </button>
              </div>
            </div>
          </div>
        </section>

        <!-- CALL PAGE -->
        <section class="page" id="page-call">
          <div class="row cols-2">
            <div class="card">
              <h3 class="title">Presence Call</h3>
              <div class="grid-2">
                <div>
                  <label for="callUser">Child</label>
                  <select id="callUser"></select>
                </div>
                <div>
                  <label for="sequence">Sequence</label>
                  <select id="sequence">
                    <option value="gentle">
                      Gentle — vibrate → light → soft tone
                    </option>
                    <option value="standard">
                      Standard — longer durations
                    </option>
                    <option value="escalate">
                      Escalate if no response (3x)
                    </option>
                  </select>
                </div>
              </div>
              <div class="grid-2" style="margin-top: 12px">
                <div>
                  <label for="strength">Strength</label>
                  <input id="strength" type="range" min="1" max="5" value="3" />
                </div>
                <div>
                  <label for="duration">Duration (seconds)</label>
                  <input
                    id="duration"
                    type="range"
                    min="5"
                    max="30"
                    value="12"
                  />
                </div>
              </div>
              <div class="grid-2" style="margin-top: 12px">
                <div>
                  <label for="dnd">Do-Not-Disturb window (school hours)</label>
                  <select id="dnd">
                    <option value="respect">Respect DND (tone muted)</option>
                    <option value="ignore">Ignore DND</option>
                  </select>
                </div>
              </div>
              <div class="toolbar" style="margin-top: 12px">
                <button class="btn" id="btnSendCall">Send Presence Call</button>
              </div>
              <div
                id="callResult"
                class="banner"
                style="margin-top: 10px; display: none"
              ></div>
            </div>

            <div class="card">
              <h3 class="title">Recent Presence Calls</h3>
              <table class="timeline">
                <thead>
                  <tr>
                    <th>When</th>
                    <th>Who</th>
                    <th>Sequence</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="callLogs"></tbody>
              </table>
            </div>
          </div>
        </section>

        <!-- SETTINGS PAGE -->
        <section class="page" id="page-settings">
          <div class="row cols-3">
            <div class="card">
              <h3 class="title">Family & Access</h3>
              <label>Parent Owner</label>
              <input value="Parent (You)" disabled />
              <label style="margin-top: 10px"
                >Secondary Parent (optional)</label
              >
              <input placeholder="Add name / phone" />
              <div class="toolbar" style="margin-top: 12px">
                <button class="btn secondary">Save</button>
              </div>
            </div>
            <div class="card">
              <h3 class="title">Device Defaults</h3>
              <label>Alert Modes Enabled</label>
              <select>
                <option>Vibrate + Light + Soft tone</option>
                <option>Vibrate + Light</option>
                <option>Vibrate only</option>
              </select>
              <label style="margin-top: 10px">SMS Retries (weak signal)</label>
              <select>
                <option>2</option>
                <option>3</option>
                <option selected>5</option>
              </select>
              <label style="margin-top: 10px">Data Retention (logs)</label>
              <select>
                <option>30 days</option>
                <option selected>90 days</option>
                <option>180 days</option>
              </select>
            </div>
            <div class="card">
              <h3 class="title">Connectivity & Help</h3>
              <p style="margin: 0 0 8px">
                Works via <b>GSM/SMS</b> after registration — no internet
                needed.
              </p>
              <p style="margin: 0 0 8px">
                Tip: GPS may take 1–2 minutes for a fresh lock in open areas.
              </p>
              <div class="toolbar">
                <button
                  class="btn ghost"
                  onclick="alert('Help opened (demo).')"
                >
                  Open Help
                </button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- Add Dependent Modal -->
    <div class="modal-backdrop" id="modalBackdrop">
      <div class="modal">
        <h3 class="title">Add Dependent</h3>
        <div class="grid-2">
          <div>
            <label for="depName">Full Name</label>
            <input id="depName" placeholder="e.g. Juan Dela Cruz" />
          </div>
          <div>
            <label for="depCategory">Category</label>
            <select id="depCategory">
              <option value="normal">Normal</option>
              <option value="child_with_disability">Child with Disability</option>
              <option value="bed_ridden">Bed Ridden</option>
              <option value="elderly">Elderly</option>
              <option value="disabled_elderly">Disabled Elderly</option>
              <option value="other">Other</option>
            </select>
          </div>
        </div>
        <div class="grid-2" style="margin-top: 12px">
          <div>
            <label for="depDevice">Device ID</label>
            <input id="depDevice" placeholder="DEVICE-XXXXX" />
          </div>
          <div>
            <label for="depSim">SIM Number (optional)</label>
            <input id="depSim" placeholder="09xxxxxxxxx" />
          </div>
        </div>
        <div class="toolbar" style="margin-top: 12px">
          <button class="btn" id="btnSaveDependent">Save</button>
          <button class="btn secondary" id="btnModalDone">Close</button>
        </div>
      </div>
    </div>

    <script>
      // ---- App state (populated from backend) ----
      let users = [];
      let activeUserId = null;
      let locationLogs = [];
      let callLogs = [];
      
      // ---- Map state ----
      let map = null;
      let markers = {};
      let currentMarker = null;

      // ---- Helpers ----
      const fmtTime = (d) => new Date(d).toLocaleString();
      const el = (sel) => document.querySelector(sel);
      const els = (sel) => Array.from(document.querySelectorAll(sel));
      function setBanner(text, type = "success") {
        const b = el("#topBanner");
        b.className = "banner " + (type === "warn" ? "warn" : "success");
        b.textContent = text;
      }
      function randOffset() {
        return (Math.random() - 0.5) * 0.003;
      }
      function statusChips(user) {
        const signal = Number.isFinite(Number(user.signal))
          ? Math.max(0, Math.min(5, Number(user.signal)))
          : 0;
        const battery =
          typeof user.battery === "number" ? `${user.battery}%` : "—";
        const lastSeen = user.lastSeen || "—";
        const signalBar = signal ? "▮".repeat(signal) : "—";
        return `
    <span class="chip">Signal: ${signalBar}</span>
    <span class="chip">Battery: ${battery}</span>
    <span class="chip">Last reply: ${lastSeen}</span>
  `;
      }

      // ---- Sidebar/Tabs ----
      const tabMap = {
        location: "page-location",
        users: "page-users",
        call: "page-call",
        settings: "page-settings",
      };
      els(".nav-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
          els(".nav-btn").forEach((b) => b.removeAttribute("aria-current"));
          btn.setAttribute("aria-current", "page");
          const tab = btn.dataset.tab;
          els(".page").forEach((p) => p.classList.remove("active"));
          el(`#${tabMap[tab]}`).classList.add("active");
          if (window.innerWidth < 860) {
            el("#sidebar").classList.remove("open");
          }
        });
      });

      // Hamburger for mobile
      el("#hamburger").addEventListener("click", () => {
        el("#sidebar").classList.toggle("open");
      });

      // ---- Populate selects ----
      function fillUserSelects() {
        const opts = users
          .map((u) => `<option value="${u.id}">${u.name}</option>`)
          .join("");
        el("#userSelect").innerHTML = opts;
        if (activeUserId) el("#userSelect").value = activeUserId;
        el("#callUser").innerHTML = opts;
        if (activeUserId) el("#callUser").value = activeUserId;
      }

      function updateDependentStats(data = []) {
        const devices = data.filter((u) => !!u.deviceId).length;
        const dependents = data.length;
        const deviceEl = el("#deviceCount");
        const dependentEl = el("#dependentCount");
        const badge = el("#dependentTotalBadge");
        if (deviceEl) deviceEl.textContent = devices;
        if (dependentEl) dependentEl.textContent = dependents;
        if (badge) {
          badge.textContent =
            dependents === 1 ? "1 dependent" : `${dependents} dependents`;
        }
      }

      function formatCategory(code) {
        const labels = {
          regular: "Normal",
          normal: "Normal",
          child_with_disability: "Child with Disability",
          bed_ridden: "Bed Ridden",
          elderly: "Elderly",
          disabled_elderly: "Disabled Elderly",
          other: "Other",
        };
        return labels[code] || "—";
      }

      function renderUserProfile() {
        const user = users.find((u) => u.id === activeUserId);
        const avatar = el("#profileAvatar");
        const nameEl = el("#profileName");
        const metaEl = el("#profileMeta");
        const deviceEl = el("#profileDevice");
        const simEl = el("#profileSim");
        const categoryEl = el("#profileCategory");
        const lastSeenEl = el("#profileLastSeen");
        const signalEl = el("#profileSignal");
        const batteryEl = el("#profileBattery");
        const coordsEl = el("#profileCoords");
        const noteEl = el("#profileNote");
        const pingBtn = el("#btnProfilePing");
        const locateBtn = el("#btnProfileLocate");

        if (!nameEl || !avatar) return;

        if (!user) {
          avatar.textContent = "?";
          nameEl.textContent = "Select a dependent";
          metaEl.textContent =
            "Choose someone from the list to view their device details.";
          deviceEl.textContent = "—";
          simEl.textContent = "—";
          categoryEl.textContent = "—";
          lastSeenEl.textContent = "—";
          signalEl.textContent = "—";
          batteryEl.textContent = "—";
          coordsEl.textContent = "—";
          noteEl.textContent =
            "Keep devices synced to get live battery, signal, and last location updates here.";
          if (pingBtn) pingBtn.disabled = true;
          if (locateBtn) locateBtn.disabled = true;
          return;
        }

        avatar.textContent = (user.name || "?").charAt(0).toUpperCase();
        nameEl.textContent = user.name;
        metaEl.textContent = formatCategory(user.category);
        deviceEl.textContent = user.deviceId || "—";
        simEl.textContent = user.sim || "—";
        categoryEl.textContent = formatCategory(user.category);
        lastSeenEl.textContent = user.lastSeen || "—";
        const signalLevel = Number.isFinite(Number(user.signal))
          ? Math.max(0, Math.min(5, Number(user.signal)))
          : null;
        signalEl.textContent = signalLevel
          ? `${"▮".repeat(signalLevel)} (${signalLevel}/5)`
          : "—";
        batteryEl.textContent =
          typeof user.battery === "number" ? `${user.battery}%` : "—";
        const hasCoords =
          user.coords &&
          typeof user.coords.lat === "number" &&
          typeof user.coords.lng === "number";
        coordsEl.textContent = hasCoords
          ? `${user.coords.lat.toFixed(5)}, ${user.coords.lng.toFixed(5)}`
          : "No recent fix";
        noteEl.textContent = user.coords
          ? "Latest GPS lock displayed. Use Quick Ping to refresh."
          : "No location yet — send a ping or ensure the device is online.";
        if (pingBtn) pingBtn.disabled = false;
        if (locateBtn) locateBtn.disabled = !(hasCoords && map);
      }

      function selectUser(id) {
        activeUserId = id;
        fillUserSelects();
        renderUserCards();
        renderUserProfile();
        const u = users.find((x) => x.id === activeUserId);
        if (u) {
          updateStatusbar(u);
          updateMap(u);
        }
      }

      window.selectUser = selectUser;

      // ---- User cards ----
      function renderUserCards() {
        el("#userCards").innerHTML = users
          .map(
            (u) => `
    <div class="item user-card ${u.id === activeUserId ? "active" : ""}" onclick="selectUser('${u.id}')">
      <div class="avatar">${(u.name || "?").charAt(0).toUpperCase()}</div>
      <div>
        <div style="font-weight:800">${u.name}</div>
        <div class="meta">Device ${u.deviceId || "—"} • SIM ${u.sim || "—"}</div>
        <div class="statusbar" style="margin-top:6px">${statusChips(u)}</div>
      </div>
      <div class="toolbar"><button class="btn secondary" onclick="quickPing('${
        u.id
      }')">Quick Ping</button></div>
    </div>`
          )
          .join("");
      }

      // ---- Map initialization ----
      function initMap() {
        const mapElement = el("#map");
        if (!mapElement) return;
        
        // Default center (you can change this to your preferred location)
        const defaultCenter = { lat: 14.5995, lng: 120.9842 }; // Manila, Philippines
        
        map = new google.maps.Map(mapElement, {
          zoom: 15,
          center: defaultCenter,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          styles: [
            {
              featureType: "poi",
              elementType: "labels",
              stylers: [{ visibility: "off" }]
            }
          ],
          mapTypeControl: true,
          streetViewControl: false,
          fullscreenControl: true,
          zoomControl: true
        });
        
        // Add click listener for map
        map.addListener('click', function(event) {
          console.log('Map clicked at:', event.latLng.lat(), event.latLng.lng());
        });
      }
      
      // ---- Map markers ----
      function addOrUpdateMarker(user) {
        if (!map || !user || !user.coords) return;
        
        const position = { lat: user.coords.lat, lng: user.coords.lng };
        
        // Remove existing marker for this user
        if (markers[user.id]) {
          markers[user.id].setMap(null);
        }
        
        // Create new marker
        const marker = new google.maps.Marker({
          position: position,
          map: map,
          title: `${user.name} - Last seen: ${user.lastSeen || 'Unknown'}`,
          icon: {
            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
              <svg width="32" height="32" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                <circle cx="16" cy="16" r="12" fill="#2563eb" stroke="#fff" stroke-width="3"/>
                <text x="16" y="20" text-anchor="middle" fill="white" font-family="Arial" font-size="12" font-weight="bold">${user.name[0]}</text>
              </svg>
            `),
            scaledSize: new google.maps.Size(32, 32),
            anchor: new google.maps.Point(16, 16)
          }
        });
        
        // Add info window
        const infoWindow = new google.maps.InfoWindow({
          content: `
            <div style="padding: 8px; font-family: Arial, sans-serif;">
              <h3 style="margin: 0 0 8px 0; color: #2563eb;">${user.name}</h3>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Device:</strong> ${user.deviceId}</p>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Last Seen:</strong> ${user.lastSeen || 'Unknown'}</p>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Signal:</strong> ${user.signal}/5</p>
              <p style="margin: 4px 0; font-size: 14px;"><strong>Battery:</strong> ${user.battery}%</p>
            </div>
          `
        });
        
        marker.addListener('click', function() {
          infoWindow.open(map, marker);
        });
        
        markers[user.id] = marker;
        
        // If this is the active user, center the map and set as current marker
        if (user.id === activeUserId) {
          map.setCenter(position);
          currentMarker = marker;
        }
      }
      
      function removeMarker(userId) {
        if (markers[userId]) {
          markers[userId].setMap(null);
          delete markers[userId];
        }
      }
      
      function centerOnUser(userId) {
        const user = users.find(u => u.id === userId);
        if (user && user.coords && map) {
          const position = { lat: user.coords.lat, lng: user.coords.lng };
          map.setCenter(position);
          map.setZoom(16);
          
          // Highlight the marker
          if (markers[userId]) {
            markers[userId].setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(() => {
              if (markers[userId]) {
                markers[userId].setAnimation(null);
              }
            }, 2000);
          }
        }
      }
      
      // ---- Map + status ----
      function updateMap(u) {
        const c = el("#coords");
        if (u && u.coords) {
          c.textContent = `Lat ${u.coords.lat.toFixed(5)}, Lng ${u.coords.lng.toFixed(5)} • Last: ${u.lastSeen ?? "—"}`;
          
          // Update map marker
          addOrUpdateMarker(u);
        } else {
          c.textContent = "—";
          if (u) {
            removeMarker(u.id);
          }
        }
      }
      function updateStatusbar(u) {
        el("#statusbar").innerHTML = statusChips(u);
      }

      // ---- Logs ----
      function renderLocationLogs() {
        const range = el("#filterRange").value;
        const st = el("#filterStatus").value;
        const now = new Date();
        const filtered = locationLogs
          .filter((row) => {
            if (range === "today") {
              const d = new Date(row.time);
              return d.toDateString() === now.toDateString();
            } else if (range === "7") {
              return now - new Date(row.time) <= 7 * 864e5;
            }
            return true;
          })
          .filter((row) => (st === "all" ? true : row.status === st));

        el("#logBody").innerHTML = filtered
          .map(
            (r) => `
    <tr>
      <td>${fmtTime(r.time)}</td>
      <td>${r.user}</td>
      <td>${r.address}</td>
      <td>${r.source}</td>
      <td><span class="tag ${r.status === "success" ? "success" : "fail"}">${
              r.status
            }</span></td>
    </tr>`
          )
          .join("");
      }

      function addLocationLog(u, ok = true) {
        const addr = ok ? "—" : "—";
        locationLogs.unshift({
          time: Date.now(),
          user: u.name,
          address: addr,
          source: "Parent Ping",
          status: ok ? "success" : "fail",
        });
        renderLocationLogs();
      }

      function exportCSV() {
        const header = ["time", "user", "address", "source", "status"];
        const rows = locationLogs.map((r) => [
          fmtTime(r.time),
          r.user,
          r.address,
          r.source,
          r.status,
        ]);
        const csv = [
          header.join(","),
          ...rows.map((r) => r.map((x) => `"${x}"`).join(",")),
        ].join("\n");
        const blob = new Blob([csv], { type: "text/csv" });
        const a = document.createElement("a");
        a.href = URL.createObjectURL(blob);
        a.download = "location_logs.csv";
        a.click();
      }

      // ---- Actions ----
      function ping() {
        const u = users.find((x) => x.id === activeUserId);
        setBanner("Sending location ping via GSM…");
        // Placeholder: simulate until device integration
        setTimeout(() => {
          const ok = Math.random() > 0.1; // 90% success
          if (ok) {
            if (u.coords) {
              u.coords.lat += randOffset();
              u.coords.lng += randOffset();
            }
            u.lastSeen = "Just now";
            updateMap(u);
            updateStatusbar(u);
            addLocationLog(u, true);
            setBanner("Location ping reply received.", "success");
          } else {
            addLocationLog(u, false);
            setBanner("Ping failed — weak signal. Try again.", "warn");
          }
        }, 1200);
      }

      function quickPing(id) {
        selectUser(id);
        ping();
      }

      async function sendPresenceCall() {
        const uid = el("#callUser").value;
        const u = users.find((x) => x.id === uid);
        const seq = el("#sequence").value;
        const strength = el("#strength").value;
        const dur = el("#duration").value;
        const dnd = el("#dnd").value;

        try {
          const res = await fetch("{{ route('dashboard.sendPresenceCall') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content,
              "Accept": "application/json",
            },
            body: JSON.stringify({ child_id: uid, sequence: seq, strength: Number(strength), duration: Number(dur), dnd }),
          });
          const data = await res.json();
          const ok = !!data.ok;
          callLogs.unshift({ time: Date.now(), user: u.name, seq, status: ok ? "sent" : "failed" });
          renderCallLogs();
          const box = el("#callResult");
          box.style.display = "block";
          box.textContent = ok
            ? `Presence Call sent to ${u.name} — ${seq}, strength ${strength}, ${dur}s (${dnd === "respect" ? "DND respected" : "DND ignored"})`
            : `Presence Call failed.`;
          setBanner(ok ? "Presence Call sent." : "Presence Call failed — check signal.", ok ? "success" : "warn");
        } catch (e) {
          setBanner("Presence Call failed — network error.", "warn");
        }
      }

      function renderCallLogs() {
        el("#callLogs").innerHTML = callLogs
          .map(
            (r) => `
    <tr><td>${fmtTime(r.time)}</td><td>${r.user}</td><td>${
              r.seq
            }</td><td><span class="tag ${
              r.status === "sent" ? "success" : "fail"
            }">${r.status}</span></td></tr>
  `
          )
          .join("");
      }

      // ---- Map helpers ----
      function openMaps() {
        const u = users.find((x) => x.id === activeUserId);
        if (u && u.coords) {
          window.open(`https://www.google.com/maps?q=${u.coords.lat},${u.coords.lng}`, "_blank");
        }
      }

      // ---- Modal ----
      function openModal() {
        el("#modalBackdrop").style.display = "flex";
      }
      function closeModal() {
        el("#modalBackdrop").style.display = "none";
      }

      // Logout handled by POST form submission above

      // ---- Init ----
      async function init() {
        // Load children
        try {
          const res = await fetch("{{ route('dashboard.children') }}", { headers: { Accept: "application/json" } });
          const payload = await res.json();
          users = payload.data || [];
        } catch (e) {
          users = [];
        }

        if (users.length > 0) {
          activeUserId = users[0].id;
        } else {
          activeUserId = null;
        }

        fillUserSelects();
        renderUserCards();
        renderUserProfile();
        updateDependentStats(users);
        
        // Initialize map
        initMap();
        
        // Update map and status after map is initialized
        setTimeout(() => {
          const initialUser =
            users.find((u) => u.id === activeUserId) || users[0] || null;
          updateMap(initialUser || null);
          updateStatusbar(
            initialUser || { signal: 0, battery: 0, lastSeen: "—" }
          );
        }, 100);

        // Load logs
        try {
          const [locRes, callRes] = await Promise.all([
            fetch("{{ route('dashboard.locationLogs') }}", { headers: { Accept: "application/json" } }),
            fetch("{{ route('dashboard.presenceCalls') }}", { headers: { Accept: "application/json" } }),
          ]);
          const locData = await locRes.json();
          const callData = await callRes.json();
          locationLogs = locData.data || [];
          callLogs = callData.data || [];
        } catch (e) {
          locationLogs = [];
          callLogs = [];
        }

        renderLocationLogs();
        renderCallLogs();

        // Events
        el("#userSelect").addEventListener("change", (e) => {
          activeUserId = e.target.value;
          const u = users.find((x) => x.id === activeUserId);
          updateMap(u);
          updateStatusbar(u);
          renderUserProfile();
        });
        el("#btnPing").addEventListener("click", ping);
        el("#btnRefresh").addEventListener("click", () => {
          const u = users.find((x) => x.id === activeUserId);
          updateMap(u);
          if (u && u.coords) {
            centerOnUser(activeUserId);
          }
          setBanner("Map refreshed.", "success");
        });
        el("#btnExport").addEventListener("click", exportCSV);
        el("#filterRange").addEventListener("change", renderLocationLogs);
        el("#filterStatus").addEventListener("change", renderLocationLogs);
        el("#btnOpenMaps").addEventListener("click", openMaps);
        el("#btnCenter").addEventListener("click", () => {
          centerOnUser(activeUserId);
          setBanner("Centered on child.", "success");
        });
        el("#btnAddChild").addEventListener("click", openModal);
        el("#btnModalDone").addEventListener("click", closeModal);
        el("#btnSaveDependent").addEventListener("click", saveDependent);
        el("#btnSendCall").addEventListener("click", sendPresenceCall);
        const profilePingBtn = el("#btnProfilePing");
        if (profilePingBtn) {
          profilePingBtn.addEventListener("click", () => {
            if (activeUserId) {
              quickPing(activeUserId);
            }
          });
        }
        const profileLocateBtn = el("#btnProfileLocate");
        if (profileLocateBtn) {
          profileLocateBtn.addEventListener("click", () => {
            if (activeUserId) {
              centerOnUser(activeUserId);
              setBanner("Centered on child.", "success");
            }
          });
        }
      }

      init();

      async function saveDependent() {
        const name = el('#depName').value.trim();
        const category = el('#depCategory').value;
        const device_id = el('#depDevice').value.trim();
        const sim_number = el('#depSim').value.trim();
        if (!name || !device_id) {
          setBanner('Name and Device ID are required.', 'warn');
          return;
        }
        try {
          const res = await fetch("{{ route('dashboard.createChild') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
              'Accept': 'application/json',
            },
            body: JSON.stringify({ name, category, device_id, sim_number })
          });
          if (!res.ok) {
            const data = await res.json().catch(()=>({}));
            const msg = data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Failed to add dependent.');
            setBanner(msg, 'warn');
            return;
          }
          const data = await res.json();
          // Push to local list
          users.push({
            id: data.id,
            name,
            category,
            deviceId: device_id,
            sim: sim_number || '—',
            signal: 0,
            battery: 0,
            lastSeen: null,
            coords: null,
          });
          selectUser(data.id);
          updateDependentStats(users);
          closeModal();
          setBanner('Dependent added successfully.', 'success');
        } catch (e) {
          setBanner('Failed to add dependent — network error.', 'warn');
        }
      }

      // ---- Session Management & Auto Logout ----
      (function() {
        const SESSION_LIFETIME_MINUTES = 15;
        const WARNING_THRESHOLD_MINUTES = 2; // Show warning 2 minutes before logout
        const CHECK_INTERVAL_MS = 30000; // Check every 30 seconds
        const SESSION_LIFETIME_MS = SESSION_LIFETIME_MINUTES * 60 * 1000;
        const WARNING_THRESHOLD_MS = WARNING_THRESHOLD_MINUTES * 60 * 1000;
        
        let lastActivityTime = Date.now();
        let warningShown = false;
        let sessionCheckInterval = null;
        let warningTimeout = null;
        let logoutTimeout = null;

        // Track user activity
        const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        activityEvents.forEach(event => {
          document.addEventListener(event, updateActivity, true);
        });

        function updateActivity() {
          const wasIdle = (Date.now() - lastActivityTime) > 60000; // More than 1 minute idle
          lastActivityTime = Date.now();
          
          // Reset warning if user becomes active again
          if (warningShown) {
            warningShown = false;
            hideWarning();
          }
          
          // If user was idle and is now active, check server session
          // This will touch the session and keep it alive
          if (wasIdle) {
            checkSessionOnActivity();
          } else {
            resetTimers();
          }
        }

        function showWarning(timeRemaining) {
          if (warningShown) return;
          warningShown = true;
          
          const minutes = Math.ceil(timeRemaining / 60);
          const message = `Your session will expire in ${minutes} minute${minutes !== 1 ? 's' : ''} due to inactivity. Please save your work.`;
          
          // Create warning banner
          const warningBanner = document.createElement('div');
          warningBanner.id = 'sessionWarning';
          warningBanner.className = 'banner warn';
          warningBanner.style.cssText = 'position: fixed; top: 60px; left: 50%; transform: translateX(-50%); z-index: 10000; max-width: 600px; padding: 12px 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);';
          warningBanner.textContent = message;
          
          // Add close button
          const closeBtn = document.createElement('button');
          closeBtn.textContent = '×';
          closeBtn.style.cssText = 'float: right; background: none; border: none; font-size: 24px; cursor: pointer; margin-left: 12px; color: inherit;';
          closeBtn.onclick = () => {
            warningBanner.remove();
            warningShown = false;
          };
          warningBanner.appendChild(closeBtn);
          
          document.body.appendChild(warningBanner);
        }

        function hideWarning() {
          const warning = document.getElementById('sessionWarning');
          if (warning) {
            warning.remove();
          }
        }

        function resetTimers() {
          if (warningTimeout) clearTimeout(warningTimeout);
          if (logoutTimeout) clearTimeout(logoutTimeout);
          
          const idleTime = Date.now() - lastActivityTime;
          const timeUntilWarning = Math.max(0, SESSION_LIFETIME_MS - WARNING_THRESHOLD_MS - idleTime);
          const timeUntilLogout = Math.max(0, SESSION_LIFETIME_MS - idleTime);
          
          if (timeUntilWarning > 0) {
            warningTimeout = setTimeout(() => {
              const remaining = SESSION_LIFETIME_MS - (Date.now() - lastActivityTime);
              showWarning(remaining);
            }, timeUntilWarning);
          }
          
          if (timeUntilLogout > 0) {
            logoutTimeout = setTimeout(() => {
              performAutoLogout();
            }, timeUntilLogout);
          } else {
            // Already past timeout, logout immediately
            performAutoLogout();
          }
        }

        function checkIdleTime() {
          // Check client-side idle time only (don't touch server session)
          const idleTime = Date.now() - lastActivityTime;
          const timeRemaining = SESSION_LIFETIME_MS - idleTime;
          
          if (timeRemaining <= WARNING_THRESHOLD_MS && timeRemaining > 0 && !warningShown) {
            showWarning(timeRemaining);
          } else if (timeRemaining <= 0) {
            performAutoLogout();
          }
        }

        async function checkSessionOnActivity() {
          // Only check server session when user becomes active (this will touch the session)
          try {
            const response = await fetch("{{ route('session.check') }}", {
              method: 'GET',
              headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
              },
              credentials: 'same-origin'
            });

            if (!response.ok || response.status === 401) {
              // Session expired or unauthorized
              performAutoLogout();
              return;
            }

            const data = await response.json();
            
            if (!data.authenticated) {
              performAutoLogout();
              return;
            }
            
            // Session is valid, reset timers since user is active
            resetTimers();
          } catch (error) {
            console.error('Session check failed:', error);
            // On error, check client-side idle time
            checkIdleTime();
          }
        }

        function performAutoLogout() {
          hideWarning();
          
          // Clear all timers
          if (sessionCheckInterval) clearInterval(sessionCheckInterval);
          if (warningTimeout) clearTimeout(warningTimeout);
          if (logoutTimeout) clearTimeout(logoutTimeout);
          
          // Show logout message
          setBanner('Your session has expired due to inactivity. Redirecting to login...', 'warn');
          
          // Submit logout form after a brief delay
          setTimeout(() => {
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) {
              logoutForm.submit();
            } else {
              // Fallback: redirect to login
              window.location.href = "{{ route('login') }}";
            }
          }, 2000);
        }

        // Start session monitoring
        function startSessionMonitoring() {
          // Check idle time periodically (doesn't touch server session)
          sessionCheckInterval = setInterval(checkIdleTime, CHECK_INTERVAL_MS);
          
          // Set initial timers based on session lifetime
          resetTimers();
        }

        // Start monitoring when page loads
        if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', startSessionMonitoring);
        } else {
          startSessionMonitoring();
        }

        // Re-check session after any fetch requests (to catch auth failures)
        const originalFetch = window.fetch;
        window.fetch = async function(...args) {
          const response = await originalFetch.apply(this, args);
          
          // If we get a 401/403, session might be expired
          if (response.status === 401 || response.status === 403) {
            const url = args[0];
            // Don't logout on session check endpoint itself
            if (typeof url === 'string' && !url.includes('/session/check')) {
              performAutoLogout();
            }
          }
          
          return response;
        };
      })();
    </script>
  </body>
 </html>


