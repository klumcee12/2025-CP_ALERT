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
          Ready — GSM/SMS mode • Real-time updates active
        </div>
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
          <span id="themeIcon">🌙</span>
          <span id="themeText" style="font-size: 14px;">Dark</span>
        </button>
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
          <div class="row cols-2">
            <div class="card">
              <h3 class="title">My Profile</h3>
              <div id="profileError" class="error-message" style="display: none; margin-bottom: 12px; padding: 8px; background: #fee; color: #c33; border-radius: 4px;"></div>
              <div id="profileSuccess" class="success-message" style="display: none; margin-bottom: 12px; padding: 8px; background: #efe; color: #3c3; border-radius: 4px;"></div>
              <label for="profileName">Full Name</label>
              <input id="profileName" type="text" placeholder="Your full name" />
              <label style="margin-top: 10px" for="profileMiddleName">Middle Name (optional)</label>
              <input id="profileMiddleName" type="text" placeholder="Middle name" />
              <label style="margin-top: 10px" for="profileEmail">Email Address</label>
              <input id="profileEmail" type="email" placeholder="your.email@example.com" />
              <div style="margin-top: 12px; padding: 8px; background: #f5f5f5; border-radius: 4px; font-size: 0.9em;">
                <div><strong>Account Created:</strong> <span id="profileCreatedAt">—</span></div>
                <div style="margin-top: 4px;"><strong>Email Verified:</strong> <span id="profileEmailVerified">—</span></div>
              </div>
              <div class="toolbar" style="margin-top: 12px">
                <button class="btn" id="btnUpdateProfile">Update Profile</button>
              </div>
            </div>
            <div class="card">
              <h3 class="title">Change Password</h3>
              <div id="passwordError" class="error-message" style="display: none; margin-bottom: 12px; padding: 8px; background: #fee; color: #c33; border-radius: 4px;"></div>
              <div id="passwordSuccess" class="success-message" style="display: none; margin-bottom: 12px; padding: 8px; background: #efe; color: #3c3; border-radius: 4px;"></div>
              <label for="currentPassword">Current Password</label>
              <input id="currentPassword" type="password" placeholder="Enter current password" />
              <label style="margin-top: 10px" for="newPassword">New Password</label>
              <input id="newPassword" type="password" placeholder="Enter new password (min. 8 characters)" />
              <label style="margin-top: 10px" for="confirmPassword">Confirm New Password</label>
              <input id="confirmPassword" type="password" placeholder="Confirm new password" />
              <div class="toolbar" style="margin-top: 12px">
                <button class="btn" id="btnUpdatePassword">Change Password</button>
              </div>
            </div>
          </div>
          <div class="row cols-3" style="margin-top: 24px">
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
          <div class="row cols-2" style="margin-top: 24px">
            <div class="card">
              <h3 class="title">Backup & Export</h3>
              <p style="margin: 0 0 12px; font-size: 0.9em; color: #666;">
                Export all your data or create a backup of your device configurations.
              </p>
              <div id="backupError" class="error-message" style="display: none; margin-bottom: 12px; padding: 8px; background: #fee; color: #c33; border-radius: 4px;"></div>
              <div id="backupSuccess" class="success-message" style="display: none; margin-bottom: 12px; padding: 8px; background: #efe; color: #3c3; border-radius: 4px;"></div>
              <div class="toolbar" style="flex-direction: column; gap: 8px; align-items: stretch;">
                <button class="btn" id="btnExportJson">📥 Export All Data (JSON)</button>
                <button class="btn secondary" id="btnExportCsv">📊 Export All Data (CSV)</button>
                <button class="btn ghost" id="btnBackup">💾 Create Backup</button>
              </div>
            </div>
            <div class="card">
              <h3 class="title">Restore Backup</h3>
              <p style="margin: 0 0 12px; font-size: 0.9em; color: #666;">
                Restore device configurations from a previously created backup file.
              </p>
              <div id="restoreError" class="error-message" style="display: none; margin-bottom: 12px; padding: 8px; background: #fee; color: #c33; border-radius: 4px;"></div>
              <div id="restoreSuccess" class="success-message" style="display: none; margin-bottom: 12px; padding: 8px; background: #efe; color: #3c3; border-radius: 4px;"></div>
              <label for="restoreFile">Backup File (JSON)</label>
              <input id="restoreFile" type="file" accept=".json" style="margin-bottom: 12px;" />
              <div class="toolbar">
                <button class="btn" id="btnRestore">📤 Restore Backup</button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>

    <!-- Add/Edit Dependent Modal -->
    <div class="modal-backdrop" id="modalBackdrop">
      <div class="modal">
        <h3 class="title" id="modalTitle">Add Dependent</h3>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal-backdrop" id="deleteModalBackdrop" style="display: none;">
      <div class="modal">
        <h3 class="title">Delete Dependent</h3>
        <p id="deleteConfirmText">Are you sure you want to delete this dependent? This action cannot be undone.</p>
        <div class="toolbar" style="margin-top: 12px">
          <button class="btn danger" id="btnConfirmDelete">Delete</button>
          <button class="btn secondary" id="btnCancelDelete">Cancel</button>
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
      
      // ---- Dark Mode ----
      function initTheme() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        const html = document.documentElement;
        html.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);
      }
      
      function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
      }
      
      function updateThemeIcon(theme) {
        const icon = el("#themeIcon");
        const text = el("#themeText");
        if (icon && text) {
          icon.textContent = theme === 'dark' ? '☀️' : '🌙';
          text.textContent = theme === 'dark' ? 'Light' : 'Dark';
        }
      }
      
      // Initialize theme on load
      initTheme();
      
      // Theme toggle handler
      el("#themeToggle")?.addEventListener("click", toggleTheme);
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
          // Load profile data when settings tab is clicked
          if (tab === "settings") {
            loadProfileData();
          }
        });
      });

      // Hamburger for mobile
      el("#hamburger").addEventListener("click", () => {
        el("#sidebar").classList.toggle("open");
      });
      
      // Close sidebar when clicking outside on mobile
      document.addEventListener("click", (e) => {
        const sidebar = el("#sidebar");
        const hamburger = el("#hamburger");
        if (window.innerWidth <= 860 && sidebar && sidebar.classList.contains("open")) {
          if (!sidebar.contains(e.target) && !hamburger.contains(e.target)) {
            sidebar.classList.remove("open");
          }
        }
      });
      
      // Touch gesture improvements for mobile
      let touchStartX = 0;
      let touchEndX = 0;
      
      document.addEventListener("touchstart", (e) => {
        touchStartX = e.changedTouches[0].screenX;
      }, { passive: true });
      
      document.addEventListener("touchend", (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
      }, { passive: true });
      
      function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
          const sidebar = el("#sidebar");
          if (window.innerWidth <= 860 && sidebar) {
            if (diff > 0 && touchStartX < 50) {
              // Swipe right from left edge - open sidebar
              sidebar.classList.add("open");
            } else if (diff < 0 && sidebar.classList.contains("open")) {
              // Swipe left - close sidebar
              sidebar.classList.remove("open");
            }
          }
        }
      }

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
      <div class="toolbar" style="flex-direction: column; gap: 6px;">
        <button class="btn secondary" onclick="event.stopPropagation(); quickPing('${u.id}')">Quick Ping</button>
        <button class="btn ghost" onclick="event.stopPropagation(); editDependent('${u.id}')" style="font-size: 0.85em;">✏️ Edit</button>
        <button class="btn ghost danger" onclick="event.stopPropagation(); confirmDeleteDependent('${u.id}')" style="font-size: 0.85em;">🗑️ Delete</button>
      </div>
    </div>`
          )
          .join("");
      }

      // ---- Map initialization ----
      function initMap() {
        const mapElement = el("#map");
        if (!mapElement) return;
        
        // Check if Google Maps API is loaded
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
          console.error('Google Maps API is not loaded. Please check your API key configuration.');
          el("#coords").textContent = "Map unavailable - API key not configured";
          return;
        }
        
        // Default center (you can change this to your preferred location)
        const defaultCenter = { lat: 14.5995, lng: 120.9842 }; // Manila, Philippines
        
        try {
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
        } catch (error) {
          console.error('Error initializing map:', error);
          el("#coords").textContent = "Map initialization failed";
        }
      }
      
      // ---- Map markers ----
      function addOrUpdateMarker(user) {
        if (!map || !user || !user.coords) return;
        
        // Ensure coordinates are numbers
        const lat = parseFloat(user.coords.lat);
        const lng = parseFloat(user.coords.lng);
        
        // Validate coordinates
        if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
          console.error('Invalid coordinates:', user.coords);
          return;
        }
        
        const position = { lat: lat, lng: lng };
        
        // Remove existing marker and label for this user
        if (markers[user.id]) {
          markers[user.id].setMap(null);
        }
        if (markers[user.id + '_label']) {
          markers[user.id + '_label'].setMap(null);
        }
        
        // Create a more visible pin-style marker
        const markerIcon = {
          url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
            <svg width="48" height="64" viewBox="0 0 48 64" xmlns="http://www.w3.org/2000/svg">
              <!-- Pin shadow -->
              <ellipse cx="24" cy="58" rx="8" ry="4" fill="#000" opacity="0.2"/>
              <!-- Pin body -->
              <path d="M 24 4 C 14 4 6 12 6 22 C 6 32 24 56 24 56 C 24 56 42 32 42 22 C 42 12 34 4 24 4 Z" fill="#dc2626" stroke="#fff" stroke-width="2"/>
              <!-- Inner circle -->
              <circle cx="24" cy="22" r="10" fill="#fff"/>
              <circle cx="24" cy="22" r="7" fill="#dc2626"/>
              <!-- Initial letter -->
              <text x="24" y="26" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="14" font-weight="bold">${user.name[0].toUpperCase()}</text>
            </svg>
          `),
          scaledSize: new google.maps.Size(48, 64),
          anchor: new google.maps.Point(24, 64)
        };
        
        // Create new marker with pin icon
        const marker = new google.maps.Marker({
          position: position,
          map: map,
          title: `${user.name} - Last seen: ${user.lastSeen || 'Unknown'}`,
          icon: markerIcon,
          animation: user.id === activeUserId ? google.maps.Animation.DROP : null,
          zIndex: user.id === activeUserId ? 1000 : 100
        });
        
        // Create a label marker with the child's name
        const labelMarker = new google.maps.Marker({
          position: position,
          map: map,
          icon: {
            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(`
              <svg width="120" height="32" viewBox="0 0 120 32" xmlns="http://www.w3.org/2000/svg">
                <rect x="0" y="0" width="120" height="32" rx="16" fill="#2563eb" opacity="0.9"/>
                <text x="60" y="20" text-anchor="middle" fill="white" font-family="Arial, sans-serif" font-size="12" font-weight="bold">${user.name}</text>
              </svg>
            `),
            scaledSize: new google.maps.Size(120, 32),
            anchor: new google.maps.Point(60, 40)
          },
          label: {
            text: user.name,
            color: 'white',
            fontSize: '12px',
            fontWeight: 'bold'
          },
          zIndex: user.id === activeUserId ? 1001 : 101
        });
        
        // Add info window with more details
        const infoWindow = new google.maps.InfoWindow({
          content: `
            <div style="padding: 12px; font-family: Arial, sans-serif; min-width: 200px;">
              <h3 style="margin: 0 0 10px 0; color: #dc2626; font-size: 18px;">📍 ${user.name}</h3>
              <div style="border-top: 1px solid #e5e7eb; padding-top: 8px;">
                <p style="margin: 6px 0; font-size: 14px;"><strong>Device ID:</strong> ${user.deviceId}</p>
                <p style="margin: 6px 0; font-size: 14px;"><strong>Last Seen:</strong> ${user.lastSeen || 'Unknown'}</p>
                <p style="margin: 6px 0; font-size: 14px;"><strong>Signal Strength:</strong> ${user.signal}/5 ${'📶'.repeat(user.signal)}</p>
                <p style="margin: 6px 0; font-size: 14px;"><strong>Battery:</strong> ${user.battery}% ${user.battery > 50 ? '🔋' : user.battery > 20 ? '🪫' : '⚠️'}</p>
                <p style="margin: 6px 0; font-size: 12px; color: #6b7280;">Coordinates: ${lat.toFixed(6)}, ${lng.toFixed(6)}</p>
              </div>
            </div>
          `
        });
        
        // Open info window on marker click
        marker.addListener('click', function() {
          infoWindow.open(map, marker);
        });
        
        // Also open info window when label is clicked
        labelMarker.addListener('click', function() {
          infoWindow.open(map, marker);
        });
        
        // Store both markers
        markers[user.id] = marker;
        markers[user.id + '_label'] = labelMarker;
        
        // If this is the active user, center the map and set as current marker
        if (user.id === activeUserId) {
          map.setCenter(position);
          map.setZoom(16);
          currentMarker = marker;
          // Pulse animation for active marker
          marker.setAnimation(google.maps.Animation.BOUNCE);
          setTimeout(() => {
            marker.setAnimation(null);
          }, 2000);
        }
      }
      
      function removeMarker(userId) {
        if (markers[userId]) {
          markers[userId].setMap(null);
          delete markers[userId];
        }
        // Also remove label marker if it exists
        if (markers[userId + '_label']) {
          markers[userId + '_label'].setMap(null);
          delete markers[userId + '_label'];
        }
      }
      
      function centerOnUser(userId) {
        const user = users.find(u => u.id === userId);
        if (user && user.coords && map) {
          // Ensure coordinates are numbers
          const lat = parseFloat(user.coords.lat);
          const lng = parseFloat(user.coords.lng);
          
          if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
            console.error('Invalid coordinates for centering:', user.coords);
            setBanner('Invalid coordinates. Cannot center map.', 'warn');
            return;
          }
          
          const position = { lat: lat, lng: lng };
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
          // Ensure coordinates are numbers and valid
          const lat = parseFloat(u.coords.lat);
          const lng = parseFloat(u.coords.lng);
          
          if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            c.textContent = `Lat ${lat.toFixed(5)}, Lng ${lng.toFixed(5)} • Last: ${u.lastSeen ?? "—"}`;
            
            // Update map marker (only if map is initialized)
            if (map && typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
              addOrUpdateMarker(u);
            }
          } else {
            c.textContent = `Invalid coordinates • Last: ${u.lastSeen ?? "—"}`;
            console.warn('Invalid coordinates for user:', u.id, u.coords);
          }
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
      async function ping() {
        const u = users.find((x) => x.id === activeUserId);
        if (!u) {
          setBanner("Select a dependent first.", "warn");
          return;
        }
        
        setBanner("Sending location ping request…");
        
        try {
          const res = await fetch("{{ route('dashboard.requestLocationPing') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content,
              "Accept": "application/json",
            },
            body: JSON.stringify({
              child_id: u.id,
            }),
          });
          
          const data = await res.json();
          const ok = !!data.ok;
          
          if (ok) {
            setBanner("Location ping sent. Waiting for device response...", "success");
            // The device will send telemetry, and the dashboard will auto-refresh every 5 seconds
          } else {
            setBanner("Location ping failed — check connection.", "warn");
          }
        } catch (e) {
          setBanner("Location ping failed — network error.", "warn");
        }
      }

      // Quick Ping from profile / user card:
      // sends a simple Presence Call with sensible defaults so the device can play a sound.
      async function quickPing(id) {
        selectUser(id);
        const u = users.find((x) => x.id === id);
        if (!u) {
          setBanner("Select a dependent first.", "warn");
          return;
        }

        // Defaults for a quick, gentle ping
        const seq = "gentle";
        const strength = 3;
        const dur = 10;
        const dnd = "respect";

        try {
          const res = await fetch("{{ route('dashboard.sendPresenceCall') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content,
              "Accept": "application/json",
            },
            body: JSON.stringify({
              child_id: id,
              sequence: seq,
              strength,
              duration: dur,
              dnd,
            }),
          });
          const data = await res.json();
          const ok = !!data.ok;
          callLogs.unshift({
            time: Date.now(),
            user: u.name,
            seq,
            status: ok ? "sent" : "failed",
          });
          renderCallLogs();
          setBanner(
            ok
              ? `Quick Ping sent to ${u.name}.`
              : "Quick Ping failed — check connection.",
            ok ? "success" : "warn"
          );
        } catch (e) {
          setBanner("Quick Ping failed — network error.", "warn");
        }
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
          // Ensure coordinates are numbers
          const lat = parseFloat(u.coords.lat);
          const lng = parseFloat(u.coords.lng);
          
          if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
            window.open(`https://www.google.com/maps?q=${lat},${lng}`, "_blank");
          } else {
            setBanner('Invalid coordinates. Cannot open in Google Maps.', 'warn');
          }
        }
      }

      // ---- Modal ----
      let editingChildId = null;
      
      function openModal() {
        editingChildId = null;
        el("#modalTitle").textContent = "Add Dependent";
        el("#depName").value = "";
        el("#depCategory").value = "normal";
        el("#depDevice").value = "";
        el("#depSim").value = "";
        el("#modalBackdrop").style.display = "flex";
      }
      
      function closeModal() {
        el("#modalBackdrop").style.display = "none";
        editingChildId = null;
      }
      
      function editDependent(id) {
        const user = users.find(u => u.id === id);
        if (!user) {
          setBanner("Dependent not found.", "warn");
          return;
        }
        
        editingChildId = id;
        el("#modalTitle").textContent = "Edit Dependent";
        el("#depName").value = user.name || "";
        // Map category back to UI format
        const uiCategory = user.category === "regular" ? "normal" : user.category;
        el("#depCategory").value = uiCategory || "normal";
        el("#depDevice").value = user.deviceId || "";
        el("#depSim").value = user.sim && user.sim !== "—" ? user.sim : "";
        el("#modalBackdrop").style.display = "flex";
      }
      
      function confirmDeleteDependent(id) {
        const user = users.find(u => u.id === id);
        if (!user) {
          setBanner("Dependent not found.", "warn");
          return;
        }
        
        el("#deleteConfirmText").textContent = `Are you sure you want to delete "${user.name}"? This will permanently delete all associated location logs and presence calls. This action cannot be undone.`;
        el("#deleteModalBackdrop").style.display = "flex";
        el("#btnConfirmDelete").onclick = () => deleteDependent(id);
      }
      
      function closeDeleteModal() {
        el("#deleteModalBackdrop").style.display = "none";
      }
      
      async function deleteDependent(id) {
        const user = users.find(u => u.id === id);
        if (!user) {
          setBanner("Dependent not found.", "warn");
          closeDeleteModal();
          return;
        }
        
        const deleteBtn = el("#btnConfirmDelete");
        const originalText = deleteBtn.textContent;
        deleteBtn.disabled = true;
        deleteBtn.textContent = "Deleting...";
        
        try {
          const res = await fetch(`/dashboard/children/${id}`, {
            method: "DELETE",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content,
              "Accept": "application/json",
            },
          });
          
          let data;
          try {
            data = await res.json();
          } catch (jsonError) {
            throw new Error(`Server returned status ${res.status}. Please check your connection.`);
          }
          
          if (!res.ok) {
            const errorMsg = data.message || "Failed to delete dependent.";
            setBanner(errorMsg, "warn");
            deleteBtn.disabled = false;
            deleteBtn.textContent = originalText;
            return;
          }
          
          // Remove from local list
          users = users.filter(u => u.id !== id);
          
          // If deleted user was active, select first available or null
          if (activeUserId === id) {
            activeUserId = users.length > 0 ? users[0].id : null;
          }
          
          fillUserSelects();
          renderUserCards();
          renderUserProfile();
          updateDependentStats(users);
          
          // Update map if needed
          if (users.length > 0 && activeUserId) {
            const u = users.find(x => x.id === activeUserId);
            if (u) {
              updateMap(u);
              updateStatusbar(u);
            }
          } else {
            updateMap(null);
            updateStatusbar({ signal: 0, battery: 0, lastSeen: "—" });
          }
          
          closeDeleteModal();
          setBanner(data.message || "Dependent deleted successfully.", "success");
        } catch (e) {
          console.error("Error deleting dependent:", e);
          const errorMsg = e.message || "Failed to delete dependent — network error. Please check your connection and try again.";
          setBanner(errorMsg, "warn");
        } finally {
          deleteBtn.disabled = false;
          deleteBtn.textContent = originalText;
        }
      }

      // Logout handled by POST form submission above

      // ---- Real-time Data Polling ----
      let realTimePollInterval = null;
      let isPolling = false;
      const POLL_INTERVAL_MS = 5000; // Poll every 5 seconds for real-time updates
      
      async function refreshChildrenData() {
        if (isPolling) return; // Prevent concurrent requests
        isPolling = true;
        
        try {
          const res = await fetch("{{ route('dashboard.children') }}", { 
            headers: { Accept: "application/json" },
            cache: 'no-cache'
          });
          const payload = await res.json();
          const newUsers = payload.data || [];
          
          // Check if data has changed
          let dataChanged = false;
          if (newUsers.length !== users.length) {
            dataChanged = true;
          } else {
            // Compare each user's data
            for (let i = 0; i < newUsers.length; i++) {
              const newUser = newUsers[i];
              const oldUser = users.find(u => u.id === newUser.id);
              
              if (!oldUser) {
                dataChanged = true;
                break;
              }
              
              // Check if critical fields changed
              if (oldUser.battery !== newUser.battery ||
                  oldUser.signal !== newUser.signal ||
                  oldUser.lastSeen !== newUser.lastSeen ||
                  (oldUser.coords?.lat !== newUser.coords?.lat) ||
                  (oldUser.coords?.lng !== newUser.coords?.lng)) {
                dataChanged = true;
                break;
              }
            }
          }
          
          if (dataChanged) {
            // Preserve activeUserId if it still exists
            const previousActiveId = activeUserId;
            users = newUsers;
            
            // Restore activeUserId if it still exists, otherwise use first user
            if (users.find(u => u.id === previousActiveId)) {
              activeUserId = previousActiveId;
            } else if (users.length > 0) {
              activeUserId = users[0].id;
            } else {
              activeUserId = null;
            }
            
            // Update UI
            fillUserSelects();
            renderUserCards();
            updateDependentStats(users);
            
            // Update map and status for active user
            const activeUser = users.find((u) => u.id === activeUserId);
            if (activeUser) {
              updateMap(activeUser);
              updateStatusbar(activeUser);
              renderUserProfile();
            }
            
            // Update banner to show real-time updates are working
            const banner = el("#topBanner");
            if (banner && !banner.textContent.includes("Real-time")) {
              const originalText = banner.textContent;
              banner.textContent = "Real-time updates active — " + originalText;
              setTimeout(() => {
                if (banner) banner.textContent = originalText;
              }, 3000);
            }
          }
        } catch (e) {
          console.error('Error refreshing children data:', e);
        } finally {
          isPolling = false;
        }
      }
      
      async function refreshLocationLogs() {
        try {
          const res = await fetch("{{ route('dashboard.locationLogs') }}", { 
            headers: { Accept: "application/json" },
            cache: 'no-cache'
          });
          const data = await res.json();
          const newLogs = data.data || [];
          
          // Check if logs have changed (compare first log timestamp)
          if (newLogs.length > 0 && locationLogs.length > 0) {
            const latestNewLog = newLogs[0];
            const latestOldLog = locationLogs[0];
            if (latestNewLog.time !== latestOldLog.time) {
              locationLogs = newLogs;
              renderLocationLogs();
            }
          } else if (newLogs.length !== locationLogs.length) {
            locationLogs = newLogs;
            renderLocationLogs();
          }
        } catch (e) {
          console.error('Error refreshing location logs:', e);
        }
      }
      
      function startRealTimePolling() {
        // Clear any existing interval
        if (realTimePollInterval) {
          clearInterval(realTimePollInterval);
        }
        
        // Start polling for real-time updates
        realTimePollInterval = setInterval(() => {
          refreshChildrenData();
          refreshLocationLogs();
        }, POLL_INTERVAL_MS);
        
        console.log('Real-time polling started (every ' + (POLL_INTERVAL_MS / 1000) + ' seconds)');
      }
      
      function stopRealTimePolling() {
        if (realTimePollInterval) {
          clearInterval(realTimePollInterval);
          realTimePollInterval = null;
          console.log('Real-time polling stopped');
        }
      }
      
      // Pause polling when page is hidden, resume when visible
      document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
          stopRealTimePolling();
        } else {
          startRealTimePolling();
          // Immediately refresh when page becomes visible
          refreshChildrenData();
          refreshLocationLogs();
        }
      });

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
        
        // Initialize map (wait for Google Maps API if needed)
        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
          initMap();
        } else {
          // Wait for Google Maps API to load
          let attempts = 0;
          const checkGoogleMaps = setInterval(() => {
            attempts++;
            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
              clearInterval(checkGoogleMaps);
              initMap();
            } else if (attempts > 50) { // 5 seconds timeout
              clearInterval(checkGoogleMaps);
              console.error('Google Maps API failed to load after 5 seconds');
              el("#coords").textContent = "Map unavailable - API not loaded";
            }
          }, 100);
        }
        
        // Update map and status after map is initialized
        setTimeout(() => {
          const initialUser =
            users.find((u) => u.id === activeUserId) || users[0] || null;
          updateMap(initialUser || null);
          updateStatusbar(
            initialUser || { signal: 0, battery: 0, lastSeen: "—" }
          );
        }, 200);

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
        
        // Start real-time polling after initial load
        startRealTimePolling();
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
          stopRealTimePolling();
        });

        // Events
        el("#userSelect").addEventListener("change", (e) => {
          activeUserId = e.target.value;
          const u = users.find((x) => x.id === activeUserId);
          updateMap(u);
          updateStatusbar(u);
          renderUserProfile();
        });
        el("#btnPing").addEventListener("click", ping);
        el("#btnRefresh").addEventListener("click", async () => {
          setBanner("Refreshing data...", "success");
          await refreshChildrenData();
          await refreshLocationLogs();
          const u = users.find((x) => x.id === activeUserId);
          updateMap(u);
          if (u && u.coords) {
            centerOnUser(activeUserId);
          }
          setBanner("Data refreshed.", "success");
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
        el("#btnCancelDelete").addEventListener("click", closeDeleteModal);
        el("#deleteModalBackdrop").addEventListener("click", (e) => {
          if (e.target.id === "deleteModalBackdrop") {
            closeDeleteModal();
          }
        });
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
        
        // Profile management handlers
        el("#btnUpdateProfile").addEventListener("click", updateProfile);
        el("#btnUpdatePassword").addEventListener("click", updatePassword);
        
        // Backup & Export handlers
        el("#btnExportJson").addEventListener("click", () => {
          window.location.href = "{{ route('dashboard.export.json') }}";
        });
        el("#btnExportCsv").addEventListener("click", () => {
          window.location.href = "{{ route('dashboard.export.csv') }}";
        });
        el("#btnBackup").addEventListener("click", () => {
          window.location.href = "{{ route('dashboard.backup') }}";
        });
        el("#btnRestore").addEventListener("click", restoreBackup);
      }

      // Profile management functions
      async function loadProfileData() {
        try {
          const res = await fetch("{{ route('profile.get') }}", {
            method: 'GET',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
          });
          
          if (!res.ok) throw new Error('Failed to load profile');
          
          const data = await res.json();
          
          el("#profileName").value = data.name || '';
          el("#profileMiddleName").value = data.middle_name || '';
          el("#profileEmail").value = data.email || '';
          
          if (data.created_at) {
            const createdDate = new Date(data.created_at);
            el("#profileCreatedAt").textContent = createdDate.toLocaleDateString() + ' ' + createdDate.toLocaleTimeString();
          }
          
          if (data.email_verified_at) {
            const verifiedDate = new Date(data.email_verified_at);
            el("#profileEmailVerified").textContent = 'Yes (' + verifiedDate.toLocaleDateString() + ')';
          } else {
            el("#profileEmailVerified").textContent = 'Not verified';
          }
        } catch (err) {
          console.error('Error loading profile:', err);
        }
      }

      async function updateProfile() {
        const name = el("#profileName").value.trim();
        const middleName = el("#profileMiddleName").value.trim();
        const email = el("#profileEmail").value.trim();
        const errorEl = el("#profileError");
        const successEl = el("#profileSuccess");
        
        errorEl.style.display = 'none';
        successEl.style.display = 'none';
        
        if (!name || !email) {
          errorEl.textContent = 'Name and email are required.';
          errorEl.style.display = 'block';
          return;
        }
        
        if (!email.includes('@')) {
          errorEl.textContent = 'Please enter a valid email address.';
          errorEl.style.display = 'block';
          return;
        }
        
        try {
          const res = await fetch("{{ route('profile.update') }}", {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
              'Accept': 'application/json',
            },
            body: JSON.stringify({
              name: name,
              middle_name: middleName || null,
              email: email,
            }),
          });
          
          const data = await res.json();
          
          if (!res.ok) {
            throw new Error(data.message || 'Failed to update profile');
          }
          
          successEl.textContent = data.message || 'Profile updated successfully!';
          successEl.style.display = 'block';
          setBanner('Profile updated successfully!', 'success');
          
          // Reload profile data to show updated info
          setTimeout(loadProfileData, 1000);
        } catch (err) {
          errorEl.textContent = err.message || 'Failed to update profile. Please try again.';
          errorEl.style.display = 'block';
          setBanner('Failed to update profile.', 'warn');
        }
      }

      async function updatePassword() {
        const currentPassword = el("#currentPassword").value;
        const newPassword = el("#newPassword").value;
        const confirmPassword = el("#confirmPassword").value;
        const errorEl = el("#passwordError");
        const successEl = el("#passwordSuccess");
        
        errorEl.style.display = 'none';
        successEl.style.display = 'none';
        
        if (!currentPassword || !newPassword || !confirmPassword) {
          errorEl.textContent = 'All password fields are required.';
          errorEl.style.display = 'block';
          return;
        }
        
        if (newPassword.length < 8) {
          errorEl.textContent = 'New password must be at least 8 characters long.';
          errorEl.style.display = 'block';
          return;
        }
        
        if (newPassword !== confirmPassword) {
          errorEl.textContent = 'New password and confirmation do not match.';
          errorEl.style.display = 'block';
          return;
        }
        
        try {
          const res = await fetch("{{ route('profile.password.update') }}", {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
              'Accept': 'application/json',
            },
            body: JSON.stringify({
              current_password: currentPassword,
              password: newPassword,
              password_confirmation: confirmPassword,
            }),
          });
          
          const data = await res.json();
          
          if (!res.ok) {
            throw new Error(data.message || 'Failed to update password');
          }
          
          successEl.textContent = data.message || 'Password updated successfully!';
          successEl.style.display = 'block';
          setBanner('Password updated successfully!', 'success');
          
          // Clear password fields
          el("#currentPassword").value = '';
          el("#newPassword").value = '';
          el("#confirmPassword").value = '';
        } catch (err) {
          errorEl.textContent = err.message || 'Failed to update password. Please try again.';
          errorEl.style.display = 'block';
          setBanner('Failed to update password.', 'warn');
        }
      }

      async function restoreBackup() {
        const fileInput = el("#restoreFile");
        const errorEl = el("#restoreError");
        const successEl = el("#restoreSuccess");
        
        errorEl.style.display = 'none';
        successEl.style.display = 'none';
        
        if (!fileInput.files || fileInput.files.length === 0) {
          errorEl.textContent = 'Please select a backup file.';
          errorEl.style.display = 'block';
          return;
        }
        
        const formData = new FormData();
        formData.append('backup_file', fileInput.files[0]);
        
        try {
          const res = await fetch("{{ route('dashboard.restore') }}", {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
              'Accept': 'application/json',
            },
            body: formData,
          });
          
          const data = await res.json();
          
          if (!res.ok) {
            throw new Error(data.message || 'Failed to restore backup');
          }
          
          let message = data.message || 'Backup restored successfully!';
          if (data.errors && data.errors.length > 0) {
            message += '\n\nWarnings:\n' + data.errors.join('\n');
          }
          
          successEl.textContent = message;
          successEl.style.display = 'block';
          setBanner('Backup restored successfully!', 'success');
          
          // Clear file input and reload children
          fileInput.value = '';
          setTimeout(() => {
            loadChildren();
          }, 1000);
        } catch (err) {
          errorEl.textContent = err.message || 'Failed to restore backup. Please try again.';
          errorEl.style.display = 'block';
          setBanner('Failed to restore backup.', 'warn');
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
        
        // Disable save button to prevent double submission
        const saveBtn = el('#btnSaveDependent');
        const originalText = saveBtn.textContent;
        saveBtn.disabled = true;
        saveBtn.textContent = editingChildId ? 'Updating...' : 'Saving...';
        
        try {
          const isEdit = editingChildId !== null;
          const url = isEdit 
            ? `/dashboard/children/${editingChildId}`
            : "{{ route('dashboard.createChild') }}";
          const method = isEdit ? 'PUT' : 'POST';
          
          const res = await fetch(url, {
            method: method,
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
              'Accept': 'application/json',
            },
            body: JSON.stringify({ name, category, device_id, sim_number })
          });
          
          let data;
          try {
            data = await res.json();
          } catch (jsonError) {
            // Response is not JSON
            throw new Error(`Server returned status ${res.status}. Please check your connection.`);
          }
          
          if (!res.ok) {
            // Handle validation errors
            let errorMsg = isEdit ? 'Failed to update dependent.' : 'Failed to add dependent.';
            if (data.errors) {
              // Laravel validation errors format: { field: [messages] }
              const firstError = Object.values(data.errors)[0];
              errorMsg = Array.isArray(firstError) ? firstError[0] : firstError;
            } else if (data.message) {
              errorMsg = data.message;
            }
            setBanner(errorMsg, 'warn');
            saveBtn.disabled = false;
            saveBtn.textContent = originalText;
            return;
          }
          
          if (isEdit) {
            // Update existing user in local list
            const userIndex = users.findIndex(u => u.id === editingChildId);
            if (userIndex !== -1) {
              users[userIndex] = {
                ...users[userIndex],
                name: data.name || name,
                category: data.category || category,
                deviceId: data.deviceId || device_id,
                sim: data.sim || sim_number || '—',
              };
            }
            selectUser(editingChildId);
            setBanner('Dependent updated successfully.', 'success');
          } else {
            // Success - add to local list
            users.push({
              id: data.id,
              name: data.name || name,
              category: data.category || category,
              deviceId: device_id,
              sim: sim_number || '—',
              signal: 0,
              battery: 0,
              lastSeen: null,
              coords: null,
            });
            selectUser(data.id);
            setBanner('Dependent added successfully.', 'success');
          }
          
          // Clear modal fields
          el('#depName').value = '';
          el('#depDevice').value = '';
          el('#depSim').value = '';
          el('#depCategory').value = 'normal';
          
          updateDependentStats(users);
          closeModal();
        } catch (e) {
          console.error('Error saving dependent:', e);
          const errorMsg = e.message || (editingChildId ? 'Failed to update dependent — network error. Please check your connection and try again.' : 'Failed to add dependent — network error. Please check your connection and try again.');
          setBanner(errorMsg, 'warn');
        } finally {
          saveBtn.disabled = false;
          saveBtn.textContent = originalText;
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


