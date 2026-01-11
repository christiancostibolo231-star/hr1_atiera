<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant Documents</title>
    
</head>
<body>
<?php ob_start(); ?>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f8f9fa;
    margin: 0;
    padding: 40px;
}
h2 { color: #1f2d3d; margin-bottom: 20px; }

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

/* Controls */
.controls {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}
.controls input {
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 280px;
}
.controls button {
    padding: 8px 14px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    background: #004aad;
    color: #fff;
    font-weight: 500;
}

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.table th, .table td {
    padding: 12px 15px;
    text-align: left;
}
.table th {
    background: #1f2d3d;
    color: #fff;
    font-weight: 500;
}
.table tr {
    border-bottom: 1px solid #eee;
}
.table tr:hover { background: #f1f5f9; }

/* Status */
.status-toggle {
    cursor: pointer;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 12px;
    color: #fff;
}
.status-active { background: #28a745; }
.status-inactive { background: #dc3545; }

/* Buttons */
.btn {
    padding: 5px 10px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    margin-right: 5px;
}
.btn-edit { background: #17a2b8; color: #fff; }
.btn-delete { background: #dc3545; color: #fff; }
.btn-preview { background: #ffc107; color: #fff; }
.btn:hover { opacity: 0.9; }

/* Modal */
#modal {
    display: none;
    position: fixed;
    top:0; left:0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.7);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
#modalContent {
    position: relative;
    width: 80%; max-width: 800px;
    background: #fff;
    border-radius: 8px;
    padding: 20px;
}
#modalContent .close-btn {
    position: absolute;
    top: 15px; right: 15px;
    background: #dc3545;
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

/* Toast */
#toast {
    position: fixed;
    top: -60px;
    right: 20px;
    background: #28a745;
    color: #fff;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: bold;
    z-index: 10000;
    transition: top 0.5s ease, opacity 0.5s ease;
    opacity: 0;
}
#toast.show { top: 20px; opacity: 1; }
</style>
</head>
<body>

<div class="container">
    <h2>Rejection Templates</h2>
    <div class="controls">
        <input type="text" id="searchInput" placeholder="Search by template name or position">
        <button onclick="addTemplate()">Add New Template</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Template Name</th>
                <th>Position</th>
                <th>Message</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal">
    <div id="modalContent">
        <button class="close-btn" onclick="closeModal()">Close</button>
        <div id="modalText"></div>
    </div>
</div>

<!-- Toast -->
<div id="toast">Action successful!</div>

<script>
// Sample data
let templates = [
    {name:"Template 1", position:"Software Engineer", message:"We regret to inform you...", active:true},
    {name:"Template 2", position:"UI/UX Designer", message:"Thank you for applying...", active:false},
    {name:"Template 3", position:"Backend Developer", message:"Unfortunately, your application...", active:true}
];

const tbody = document.getElementById('tableBody');

// Render table
function renderTable(data){
    tbody.innerHTML = '';
    data.forEach((t,i)=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${t.name}</td>
            <td>${t.position}</td>
            <td>${t.message.substring(0,30)}...</td>
            <td><span class="status-toggle ${t.active?'status-active':'status-inactive'}" onclick="toggleStatus(${i})">${t.active?'ACTIVE':'INACTIVE'}</span></td>
            <td>
                <button class="btn btn-preview" onclick="previewTemplate(${i})">Preview</button>
                <button class="btn btn-edit" onclick="editTemplate(${i})">Edit</button>
                <button class="btn btn-delete" onclick="deleteTemplate(${i})">Delete</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Actions
function previewTemplate(i){
    const modal = document.getElementById('modal');
    document.getElementById('modalText').innerText = templates[i].message;
    modal.style.display = 'flex';
}
function closeModal(){ document.getElementById('modal').style.display='none'; }

function toggleStatus(i){
    templates[i].active = !templates[i].active;
    showToast(`Template ${templates[i].active?'activated':'deactivated'} successfully!`);
    renderTable(templates);
}
function addTemplate(){
    const name = prompt("Template Name:");
    const position = prompt("Position:");
    const message = prompt("Message:");
    if(name && position && message){
        templates.push({name, position, message, active:true});
        showToast("Template added successfully!");
        renderTable(templates);
    }
}
function editTemplate(i){
    const name = prompt("Template Name:", templates[i].name);
    const position = prompt("Position:", templates[i].position);
    const message = prompt("Message:", templates[i].message);
    if(name && position && message){
        templates[i] = {...templates[i], name, position, message};
        showToast("Template updated successfully!");
        renderTable(templates);
    }
}
function deleteTemplate(i){
    if(confirm("Are you sure you want to delete this template?")){
        templates.splice(i,1);
        showToast("Template deleted successfully!");
        renderTable(templates);
    }
}

// Toast
function showToast(msg){
    const toast = document.getElementById('toast');
    toast.innerText = msg;
    toast.classList.add('show');
    setTimeout(()=>{ toast.classList.remove('show'); }, 3000);
}

// Search
document.getElementById('searchInput').addEventListener('input', e=>{
    const q = e.target.value.toLowerCase();
    const filtered = templates.filter(t=>t.name.toLowerCase().includes(q) || t.position.toLowerCase().includes(q));
    renderTable(filtered);
});

renderTable(templates);
</script>

<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>
</body>
</html>
