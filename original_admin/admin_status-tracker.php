<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Status Tracker | Applicants</title>
</head>
<body>

<?php ob_start(); ?>


<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 40px;
}
.status-tracker-container {
    max-width: 1200px;
    margin: 0 auto;
    background:#fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}
h2 { font-size:28px; color:#1f2d3d; margin-bottom:20px; }
.status-controls { display:flex; justify-content:flex-end; margin-bottom:15px; }
.status-search { padding:6px 12px; border-radius:8px; border:1px solid #ccc; width:250px; }
.status-tracker-table { width:100%; border-collapse: collapse; font-size:14px; cursor:pointer; }
.status-tracker-table th, .status-tracker-table td { padding:12px 15px; text-align:left; }
.status-tracker-table th { background:#1f2d3d; color:#fff; font-weight:500; }
.status-tracker-table tr { border-bottom:1px solid #eee; }
.status-tracker-table tr:hover { background:#f1f5f9; }

/* Status Labels */
.status { display:inline-block; padding:4px 10px; border-radius:12px; font-weight:500; font-size:12px; color:#fff; text-align:center; }
.status.pending{ background:#ffc107; }
.status.screening{ background:#17a2b8; }
.status.interview{ background:#6f42c1; }
.status.assessment{ background:#fd7e14; }
.status.offer{ background:#20c997; }
.status.completed{ background:#28a745; }
.status.rejected{ background:#dc3545; }

/* Progress Bar */
.progress-bar-container { background:#eee; border-radius:12px; overflow:hidden; height:8px; width:100%; margin-top:5px; }
.progress-bar { height:8px; border-radius:12px; }
.tracker-definition { margin-top:12px; font-size:13px; color:#888; }

/* ===== MODAL ===== */
.modal { display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; overflow:auto; background: rgba(0,0,0,0.5); }
.modal-content { background:#fff; margin:10% auto; padding:20px; border-radius:12px; width:90%; max-width:600px; position:relative; box-shadow:0 5px 20px rgba(0,0,0,0.3);}
.close-btn { position:absolute; top:12px; right:16px; font-size:22px; font-weight:bold; color:#333; cursor:pointer; }
.close-btn:hover { color:#000; }
.modal h3 { margin-top:0; color:#1f2d3d; }
.modal p { margin:8px 0; }
.progress-bar-large { height:20px; border-radius:12px; margin-top:8px; }
</style>
</head>
<body>

<div class="status-tracker-container">
    <h2>Applicant Status Tracker</h2>
    <div class="status-controls">
        <input type="text" id="searchInput" class="status-search" placeholder="Search by name, position, or status">
    </div>
    <table class="status-tracker-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Applicant Name</th>
            <th>Position</th>
            <th>Date Applied</th>
            <th>Status</th>
            <th>Progress</th>
            <th>Assignee</th>
            <th>Next Action</th>
            <th>Comments</th>
            <th>Documents</th>
        </tr>
    </thead>
    <tbody id="applicantTableBody"></tbody>
</table>
    <p class="tracker-definition">Status colors: Pending, Screening, Interview, Assessment, Offer, Hired, Rejected.</p>
</div>

<!-- MODAL -->
<div id="applicantModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="modalClose">&times;</span>
        <h3 id="modalName">John Doe</h3>
        <p><strong>Position:</strong> <span id="modalPosition"></span></p>
        <p><strong>Date Applied:</strong> <span id="modalDate"></span></p>
        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
        <p><strong>Assignee:</strong> <span id="modalAssignee"></span></p>
        <p><strong>Next Action:</strong> <span id="modalNext"></span></p>
        <p><strong>Comments:</strong> <span id="modalComments"></span></p>
        <p><strong>Documents:</strong> <span id="modalDocs"></span></p>
        <div>
            <strong>Progress:</strong>
            <div class="progress-bar-container">
                <div id="modalProgress" class="progress-bar progress-bar-large"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Sample applicants
const applicants = [
    {name:"John Doe", position:"Software Engineer", date:"2025-10-15", status:"pending", progress:20, assignee:"HR A", next:"Schedule Interview", comments:"Resume review done", docs:"<a href='#'>Resume.pdf</a>"},
    {name:"Jane Smith", position:"UI/UX Designer", date:"2025-10-12", status:"screening", progress:40, assignee:"HR B", next:"Send Assessment", comments:"Portfolio looks good", docs:"<a href='#'>Portfolio.pdf</a>"},
    {name:"Mark Lee", position:"Backend Developer", date:"2025-10-10", status:"offer", progress:80, assignee:"HR C", next:"Finalize Offer", comments:"Offer extended", docs:"<a href='#'>Resume.pdf</a>"},
    {name:"Alice Tan", position:"QA Engineer", date:"2025-10-08", status:"rejected", progress:100, assignee:"HR A", next:"Send Rejection", comments:"Does not meet criteria", docs:"<a href='#'>Resume.pdf</a>"}
];

// Render table
const tbody = document.getElementById('applicantTableBody');
function renderTable(data){
    tbody.innerHTML='';
    data.forEach((app,index)=>{
        const tr = document.createElement('tr');
        tr.innerHTML=`
            <td>${index + 1}</td>
            <td>${app.name}</td>
            <td>${app.position}</td>
            <td>${app.date}</td>
            <td><span class="status ${app.status}">${app.status.toUpperCase()}</span></td>
            <td>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width:${app.progress}%;background:${getProgressColor(app.progress)};"></div>
                </div>
            </td>
            <td>${app.assignee}</td>
            <td>${app.next}</td>
            <td>${app.comments}</td>
            <td>${app.docs}</td>
        `;
        tr.addEventListener('click', ()=>openModal(app));
        tbody.appendChild(tr);
    });
}

// Progress color
function getProgressColor(p){ return p<=20?'#ffc107':p<=40?'#17a2b8':p<=60?'#6f42c1':p<=80?'#fd7e14':p<=100?'#28a745':'#ccc'; }

renderTable(applicants);

// Search filter
document.getElementById('searchInput').addEventListener('input', e=>{
    const q = e.target.value.toLowerCase();
    const filtered = applicants.filter(a=>a.name.toLowerCase().includes(q) || a.position.toLowerCase().includes(q) || a.status.toLowerCase().includes(q));
    renderTable(filtered);
});

// Modal logic
const modal = document.getElementById('applicantModal');
const modalClose = document.getElementById('modalClose');
function openModal(app){
    document.getElementById('modalName').textContent = app.name;
    document.getElementById('modalPosition').textContent = app.position;
    document.getElementById('modalDate').textContent = app.date;
    document.getElementById('modalStatus').textContent = app.status.toUpperCase();
    document.getElementById('modalAssignee').textContent = app.assignee;
    document.getElementById('modalNext').textContent = app.next;
    document.getElementById('modalComments').textContent = app.comments;
    document.getElementById('modalDocs').innerHTML = app.docs;
    const progressBar = document.getElementById('modalProgress');
    progressBar.style.width = app.progress+'%';
    progressBar.style.background = getProgressColor(app.progress);
    modal.style.display = 'block';
}
modalClose.onclick = ()=> modal.style.display='none';
window.onclick = e=> { if(e.target==modal) modal.style.display='none'; };
</script>
<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>
</body>
</html>
