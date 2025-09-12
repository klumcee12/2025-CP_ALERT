<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ALERT+ Parent Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ asset('ccs/dashboard.css') }}">
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
                <div>Map preview</div>
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
          <div class="row">
            <div class="card">
              <h3 class="title">Family & Devices</h3>
              <div class="toolbar">
                <button class="btn" id="btnAddChild">Register Device</button>
              </div>
              <div class="list" id="userCards"></div>
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

    <!-- Register Device Modal (demo) -->
    <div class="modal-backdrop" id="modalBackdrop">
      <div class="modal">
        <h3 class="title">Register Device (Demo)</h3>
        <ol>
          <li>Enter device ID / scan QR</li>
          <li>Set parent phone numbers on device</li>
          <li>Test Ping</li>
        </ol>
        <div class="toolbar">
          <button class="btn" id="btnModalDone">Done</button>
        </div>
      </div>
    </div>

    <script>
      // ---- App state (populated from backend) ----
      let users = [];
      let activeUserId = null;
      let locationLogs = [];
      let callLogs = [];

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
        return `
    <span class="chip">Signal: ${"▮".repeat(user.signal)}</span>
    <span class="chip">Battery: ${user.battery}%</span>
    <span class="chip">Last reply: ${user.lastSeen}</span>
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

      // ---- User cards ----
      function renderUserCards() {
        el("#userCards").innerHTML = users
          .map(
            (u) => `
    <div class="item">
      <div class="avatar">${u.name[0]}</div>
      <div>
        <div style="font-weight:800">${u.name}</div>
        <div class="meta">Device ${u.deviceId} • SIM ${u.sim}</div>
        <div class="statusbar" style="margin-top:6px">${statusChips(u)}</div>
      </div>
      <div class="toolbar"><button class="btn secondary" onclick="quickPing('${
        u.id
      }')">Quick Ping</button></div>
    </div>`
          )
          .join("");
      }

      // ---- Map + status ----
      function updateMap(u) {
        const c = el("#coords");
        if (u && u.coords) {
          c.textContent = `Lat ${u.coords.lat.toFixed(5)}, Lng ${u.coords.lng.toFixed(5)} • Last: ${u.lastSeen ?? "—"}`;
        } else {
          c.textContent = "—";
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
        activeUserId = id;
        fillUserSelects();
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
        }

        fillUserSelects();
        renderUserCards();
        updateMap(users[0] || null);
        updateStatusbar(users[0] || { signal: 0, battery: 0, lastSeen: "—" });

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
        });
        el("#btnPing").addEventListener("click", ping);
        el("#btnRefresh").addEventListener("click", () => {
          const u = users.find((x) => x.id === activeUserId);
          updateMap(u);
          setBanner("Map refreshed.", "success");
        });
        el("#btnExport").addEventListener("click", exportCSV);
        el("#filterRange").addEventListener("change", renderLocationLogs);
        el("#filterStatus").addEventListener("change", renderLocationLogs);
        el("#btnOpenMaps").addEventListener("click", openMaps);
        el("#btnCenter").addEventListener("click", () => {
          const u = users.find((x) => x.id === activeUserId);
          updateMap(u);
          setBanner("Centered on child.", "success");
        });
        el("#btnAddChild").addEventListener("click", openModal);
        el("#btnModalDone").addEventListener("click", closeModal);
        el("#btnSendCall").addEventListener("click", sendPresenceCall);
      }

      init();
    </script>
  </body>
 </html>


