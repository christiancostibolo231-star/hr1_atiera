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

.doc-container {
    max-width: 1200px;
    margin: 0 auto;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.doc-controls {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}
.doc-search {
    padding: 8px 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    width: 280px;
}

.doc-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.doc-table th, .doc-table td {
    padding: 12px 15px;
    text-align: left;
}
.doc-table th {
    background: #1f2d3d;
    color: #fff;
    font-weight: 500;
}
.doc-table tr {
    border-bottom: 1px solid #eee;
}
.doc-table tr:hover { background: #f1f5f9; }
.doc-table a { text-decoration: none; color: #004aad; font-weight: 500; }
.doc-table a:hover { text-decoration: underline; }

.status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    color: #fff;
    text-align: center;
}
.status.pending { background: #ffc107; }
.status.verified { background: #28a745; }
.status.incomplete { background: #dc3545; }

.btn {
    padding: 5px 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 12px;
    margin-right: 5px;
}
.btn-preview { background: #17a2b8; color: #fff; }
.btn-download { background: #004aad; color: #fff; }
.btn:hover { opacity: 0.9; }

.doc-legend {
    margin-top: 12px;
    font-size: 13px;
    color: #888;
}

/* --- Modal Styles --- */
#previewModal {
    display: none;
    position: fixed;
    top:0; left:0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.7);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
#previewModalContent {
    position: relative;
    width: 80%; max-width: 900px;
    background: #fff;
    border-radius: 8px;
    padding: 20px;
}
#previewModal iframe {
    width: 100%;
    height: 500px;
    border: none;
    border-radius: 6px;
}
#previewModal .close-btn {
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

/* --- Toast Notification --- */
#toast {
    position: fixed;
    top: 20px;
    right: 20px; /* upper-right corner */
    background: #28a745;
    color: #fff;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    font-weight: 500;
    z-index: 10000;
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.5s ease;
}
#toast.show {
    opacity: 1;
    transform: translateY(0);
}
</style>
</head>
<body>

<div class="doc-container">
    <h2>Applicant Documents</h2>
    <div class="doc-controls">
        <input type="text" id="searchInput" class="doc-search" placeholder="Search by name, position, or document type">
    </div>

    <table class="doc-table">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Position</th>
                <th>Document Type</th>
                <th>File</th>
                <th>Upload Date</th>
                <th>Status</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="docTableBody"></tbody>
    </table>

    <p class="doc-legend">
        Status colors: Pending (Yellow), Verified (Green), Incomplete (Red). Use Preview to view files, Download to save.
    </p>
</div>

<!-- Modal -->
<div id="previewModal">
    <div id="previewModalContent">
        <button class="close-btn" onclick="closeModal()">Close</button>
        <iframe id="modalIframe" src=""></iframe>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast"><strong>Download successfully!</strong></div>

<script>
// Sample data
const documents = [
    {name:"John Doe", position:"Software Engineer", type:"Resume", file:"Resume.pdf", date:"2025-10-15", status:"pending", notes:"Needs verification"},
    {name:"Jane Smith", position:"UI/UX Designer", type:"Portfolio", file:"Portfolio.pdf", date:"2025-10-12", status:"verified", notes:"Looks great"},
    {name:"Mark Lee", position:"Backend Developer", type:"Certificate", file:"Cert.pdf", date:"2025-10-10", status:"incomplete", notes:"Missing signature"},
    {name:"Alice Tan", position:"QA Engineer", type:"Resume", file:"Resume.pdf", date:"2025-10-08", status:"verified", notes:"Checked and approved"}
];

// Render table
const tbody = document.getElementById('docTableBody');
function renderTable(data){
    tbody.innerHTML = '';
    data.forEach(doc=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${doc.name}</td>
            <td>${doc.position}</td>
            <td>${doc.type}</td>
            <td><a href="#">${doc.file}</a></td>
            <td>${doc.date}</td>
            <td><span class="status ${doc.status}">${doc.status.toUpperCase()}</span></td>
            <td>${doc.notes}</td>
            <td>
                <button class="btn btn-preview" onclick="previewFile('${doc.file}')">Preview</button>
                <button class="btn btn-download" onclick="downloadFile('${doc.file}')">Download</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Modal preview
function previewFile(file){
    const modal = document.getElementById('previewModal');
    const iframe = document.getElementById('modalIframe');
    iframe.src = file; // Replace with actual file URL
    modal.style.display = 'flex';
}
function closeModal(){
    const modal = document.getElementById('previewModal');
    const iframe = document.getElementById('modalIframe');
    iframe.src = '';
    modal.style.display = 'none';
}

// Download with toast
function downloadFile(file){
    // Trigger actual download here if needed
    const toast = document.getElementById('toast');
    toast.classList.add('show');
    setTimeout(()=>{ toast.classList.remove('show'); }, 3000);
}

renderTable(documents);

// Search filter
document.getElementById('searchInput').addEventListener('input', e=>{
    const q = e.target.value.toLowerCase();
    const filtered = documents.filter(d=>
        d.name.toLowerCase().includes(q) || 
        d.position.toLowerCase().includes(q) || 
        d.type.toLowerCase().includes(q)
    );
    renderTable(filtered);
});
</script>

<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>
</body>
</html>
