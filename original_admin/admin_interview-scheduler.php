<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Interview Scheduler | Company Calendar</title>

  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
    }

    /* ✅ Notification Alert */
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #28a745;
      color: #fff;
      padding: 12px 18px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      font-weight: 500;
      opacity: 0;
      transform: translateY(-10px);
      transition: opacity 0.4s ease, transform 0.4s ease;
      z-index: 2000;
    }
    .notification.show {
      opacity: 1;
      transform: translateY(0);
    }

    .calendar-container {
      width: 95%;
      max-width: 1200px;
      margin: 40px auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      padding: 25px;
      position: relative;
    }

    h2 {
      color: #004AAD;
      text-align: center;
      font-weight: 600;
      margin-bottom: 25px;
      font-size: 28px;
    }

    .settings-btn {
      position: absolute;
      top: 25px;
      right: 30px;
      background: #004AAD;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 8px 14px;
      cursor: pointer;
      font-weight: 500;
      transition: 0.3s;
    }
    .settings-btn:hover { background: #00337a; }

    #calendar {
      max-width: 1150px;
      margin: 0 auto;
      font-size: 15px;
    }

    /* Alerts Section */
    .alerts-section {
      margin-top: 40px;
      background: #f9fafc;
      border-radius: 10px;
      padding: 15px 20px;
    }

    .alert-card {
      border-radius: 10px;
      padding: 12px 16px;
      margin-bottom: 10px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #fff;
    }

    .alert-info { background: #007bff; }
    .alert-warning { background: #ffc107; color: #000; }
    .alert-emergency { background: #dc3545; }

    .alert-controls button {
      margin-left: 5px;
      border: none;
      border-radius: 6px;
      padding: 5px 10px;
      cursor: pointer;
      font-size: 13px;
    }

    .btn-edit { background: #17a2b8; color: #fff; }
    .btn-remove { background: #343a40; color: #fff; }

    /* Interview Table */
    .interview-table-container { margin-top: 40px; }

    .interview-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14.5px;
      margin-top: 15px;
    }

    th, td {
      padding: 12px 16px;
      border-bottom: 1px solid #eee;
      text-align: center;
      vertical-align: middle;
    }

    th {
      background: #004AAD;
      color: #fff;
      font-weight: 600;
    }

    tr:hover { background: #f9fafc; }

    /* Status Colors */
    .status-badge {
      display: inline-block;
      padding: 6px 10px;
      border-radius: 6px;
      color: #fff;
      font-size: 13px;
      font-weight: 500;
      text-transform: capitalize;
    }
    .status-scheduled { background: #007bff; }
    .status-done { background: #28a745; }
    .status-ongoing { background: #ffc107; color: #000; }
    .status-denied { background: #dc3545; }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.4);
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background: white;
      border-radius: 12px;
      padding: 20px 25px;
      width: 450px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .modal-content h3 {
      color: #004AAD;
      text-align: center;
      margin-bottom: 15px;
    }

    .modal-content input, .modal-content textarea, .modal-content select {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-bottom: 12px;
      font-family: 'Poppins', sans-serif;
    }

    .modal-footer {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }

    .btn {
      border: none;
      border-radius: 6px;
      padding: 8px 14px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .btn-save { background: #004AAD; color: white; }
    .btn-save:hover { background: #00337a; }

    .btn-cancel { background: #dc3545; color: white; }
    .btn-cancel:hover { background: #b02a37; }

    #pickerModal {
      display: none;
      position: fixed;
      z-index: 1100;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.4);
      align-items: center;
      justify-content: center;
    }

    .picker-content {
      background: white;
      border-radius: 10px;
      padding: 25px;
      width: 420px;
      text-align: center;
      box-shadow: 0 5px 20px rgba(0,0,0,0.3);
      max-height: 80vh;
      overflow-y: auto;
    }

    .picker-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }

    .picker-item {
      padding: 10px 12px;
      border-radius: 6px;
      background: #f2f2f2;
      cursor: pointer;
      transition: 0.2s;
      font-size: 14px;
    }

    .picker-item:hover {
      background: #004AAD;
      color: white;
    }
  </style>
</head>
<body>

<!-- ✅ Notification container -->
<div id="notification" class="notification"></div>

<?php ob_start(); ?>

<div class="calendar-container">
  <h2>Interview Scheduler (Company Calendar)</h2>
  <button class="settings-btn" onclick="openSettingsModal()">⚙ Settings</button>
  <div id="calendar"></div>

  <!-- Alerts Section -->
  <div id="alertsSection" class="alerts-section" style="display:none;">
    <h3 style="color:#004AAD;">Company Alerts & Notices</h3>
    <div id="alertList"></div>
  </div>

  <!-- Interview Table -->
  <div class="interview-table-container">
    <h3 style="color:#004AAD; margin-bottom:10px;">Scheduled Interviews</h3>
    <table class="interview-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Applicant Name</th>
          <th>Position</th>
          <th>Interview Date & Time</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="interviewTableBody"></tbody>
    </table>
  </div>
</div>

<!-- Settings Modal -->
<div id="settingsModal" class="modal">
  <div class="modal-content">
    <h3>Create Alert / Notice</h3>
    <input type="text" id="alertTitle" placeholder="Title (e.g. Fire Drill, Power Maintenance)">
    <label>Start Date & Time</label>
    <input type="datetime-local" id="alertStart">
    <label>End Date & Time</label>
    <input type="datetime-local" id="alertEnd">
    <label>Type</label>
    <select id="alertType">
      <option value="info">Info</option>
      <option value="warning">Warning</option>
      <option value="emergency">Emergency</option>
    </select>
    <label>Description</label>
    <textarea id="alertDescription" rows="3" placeholder="Enter alert details..."></textarea>
    <div class="modal-footer">
      <button class="btn btn-cancel" onclick="closeSettingsModal()">Cancel</button>
      <button class="btn btn-save" onclick="addAlert()">Add Alert</button>
    </div>
  </div>
</div>

<!-- Year/Month Picker -->
<div id="pickerModal">
  <div class="picker-content">
    <h3 id="pickerTitle">Select Year</h3>
    <div id="pickerGrid" class="picker-grid"></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
  let calendar, pickerModal, pickerGrid;
  const alertsSection = document.getElementById("alertsSection");
  const alertList = document.getElementById("alertList");
  const interviewTableBody = document.getElementById("interviewTableBody");
  const settingsModal = document.getElementById("settingsModal");

  const sampleApplicants = [
    { name: "Maria Santos", position: "Software Engineer" },
    { name: "Juan Dela Cruz", position: "Therapist" },
    { name: "Angela Reyes", position: "Marketing Assistant" },
    { name: "Carlo Mendoza", position: "HR Specialist" },
    { name: "Patricia Cruz", position: "Graphic Designer" },
  ];
  const statuses = ["Scheduled", "Done", "On Going", "Denied"];

  document.addEventListener("DOMContentLoaded", function() {
    pickerModal = document.getElementById("pickerModal");
    pickerGrid = document.getElementById("pickerGrid");
    const calendarEl = document.getElementById("calendar");

    calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: "dayGridMonth",
      height: 750,
      selectable: true,
      headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,listWeek"
      }
    });
    calendar.render();

    setTimeout(() => {
      const titleEl = document.querySelector(".fc-toolbar-title");
      titleEl.style.cursor = "pointer";
      titleEl.style.color = "#004AAD";
      titleEl.title = "Click to change month/year";
      titleEl.addEventListener("click", openYearPicker);
    }, 200);

    generateRandomInterviews();
  });

  function openSettingsModal() { settingsModal.style.display = "flex"; }
  function closeSettingsModal() { settingsModal.style.display = "none"; }

  function addAlert() {
    const title = document.getElementById("alertTitle").value.trim();
    const start = document.getElementById("alertStart").value;
    const end = document.getElementById("alertEnd").value;
    const type = document.getElementById("alertType").value;
    const desc = document.getElementById("alertDescription").value.trim();
    if (!title || !start || !end || !desc) return alert("Please fill all fields.");

    const div = document.createElement("div");
    div.className = `alert-card alert-${type}`;
    div.innerHTML = `
      <div>
        <strong>${title}</strong><br>
        ${new Date(start).toLocaleString()} → ${new Date(end).toLocaleString()}<br>
        ${desc}
      </div>
      <div class="alert-controls">
        <button class="btn-edit" onclick="editAlert(this)">Edit</button>
        <button class="btn-remove" onclick="removeAlert(this)">Remove</button>
      </div>
    `;
    alertList.appendChild(div);
    alertsSection.style.display = "block";
    closeSettingsModal();
  }

  function editAlert(btn) {
    const parent = btn.closest(".alert-card");
    alert("Editing alert: " + parent.querySelector("strong").innerText);
  }

  function removeAlert(btn) {
    btn.closest(".alert-card").remove();
    if (!alertList.children.length) alertsSection.style.display = "none";
    showNotification("✅ Notification successfully removed");
  }

  function showNotification(message) {
    const notif = document.getElementById("notification");
    notif.textContent = message;
    notif.classList.add("show");
    setTimeout(() => notif.classList.remove("show"), 3000);
  }

  function generateRandomInterviews() {
    const now = new Date();
    sampleApplicants.forEach(a => {
      const d = new Date(now);
      d.setDate(now.getDate() + Math.floor(Math.random() * 10));
      d.setHours(9 + Math.floor(Math.random() * 8));
      const status = statuses[Math.floor(Math.random() * statuses.length)];
      calendar.addEvent({ title: `${a.name} - ${a.position}`, start: d, backgroundColor: "#004AAD" });
      addToTable(a.name, a.position, d, status);
    });
  }

  function addToTable(name, position, dateTime, status) {
    const row = document.createElement("tr");
    const count = interviewTableBody.children.length + 1;
    const badgeClass = `status-${status.toLowerCase().replace(" ", "")}`;
    row.innerHTML = `<td>${count}</td><td>${name}</td><td>${position}</td><td>${new Date(dateTime).toLocaleString()}</td><td><span class="status-badge ${badgeClass}">${status}</span></td>`;
    interviewTableBody.appendChild(row);
  }

  function openYearPicker() {
    pickerModal.style.display = "flex";
    document.getElementById("pickerTitle").innerText = "Select Year";
    pickerGrid.innerHTML = "";
    const yNow = new Date().getFullYear();
    for (let y = yNow - 20; y <= yNow + 20; y++) {
      const div = document.createElement("div");
      div.className = "picker-item";
      div.textContent = y;
      div.onclick = () => openMonthPicker(y);
      pickerGrid.appendChild(div);
    }
  }

  function openMonthPicker(y) {
    document.getElementById("pickerTitle").innerText = `Select Month (${y})`;
    pickerGrid.innerHTML = "";
    const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    months.forEach((m,i)=>{
      const div=document.createElement("div");
      div.className="picker-item";
      div.textContent=m;
      div.onclick=()=>{calendar.gotoDate(new Date(y,i,1)); pickerModal.style.display="none";};
      pickerGrid.appendChild(div);
    });
  }

  window.onclick = e => {
    if (e.target===settingsModal) closeSettingsModal();
    if (e.target===pickerModal) pickerModal.style.display="none";
  };
</script>

<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>
</body>
</html>
