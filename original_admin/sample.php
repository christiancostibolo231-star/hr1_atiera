<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Evaluation Management</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background: #f2f4f7;
    }

    header {
      background: linear-gradient(135deg, #1f4b99, #3a7bd5);
      color: white;
      padding: 15px 25px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
      margin: 0;
      font-size: 22px;
      letter-spacing: 0.5px;
    }

    main {
      padding: 25px;
      max-width: 1100px;
      margin: auto;
    }

    .controls {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
      margin-bottom: 20px;
    }

    input, select, button {
      padding: 10px 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    input:focus, select:focus {
      outline: none;
      border-color: #3a7bd5;
      box-shadow: 0 0 3px #3a7bd5;
    }

    button {
      cursor: pointer;
      background: #1f4b99;
      color: white;
      border: none;
      transition: 0.2s ease;
      font-weight: 500;
    }

    button:hover {
      background: #16386f;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 12px 14px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background: #f8f9fa;
      text-transform: uppercase;
      font-size: 13px;
      letter-spacing: 0.3px;
    }

    tr:hover {
      background: #f9f9f9;
    }

    .modal-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .modal {
      background: white;
      padding: 25px;
      border-radius: 10px;
      width: 400px;
      max-width: 90%;
      box-shadow: 0 3px 10px rgba(0,0,0,0.2);
      animation: slideDown 0.25s ease;
    }

    @keyframes slideDown {
      from {transform: translateY(-20px); opacity: 0;}
      to {transform: translateY(0); opacity: 1;}
    }

    .modal h2 {
      margin-top: 0;
      margin-bottom: 15px;
      font-size: 18px;
      color: #1f4b99;
    }

    .modal label {
      display: block;
      font-weight: 600;
      font-size: 13px;
      margin-bottom: 5px;
    }

    .modal input, .modal select {
      width: 100%;
      padding: 8px 10px;
      margin-bottom: 12px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .modal-actions {
      text-align: right;
      margin-top: 10px;
    }

    .modal button {
      padding: 8px 14px;
      border-radius: 5px;
    }

    .remove-btn {
      background: #c0392b;
    }

    .remove-btn:hover {
      background: #922b21;
    }

    .no-data {
      text-align: center;
      padding: 20px;
      color: #666;
    }
  </style>
</head>
<body>
  <header><h1>Performance Evaluation (Admin)</h1></header>
  <main>
    <div class="controls">
      <input type="text" id="searchInput" placeholder="Search by employee or reviewer...">
      <button id="addBtn">+ Add Evaluation</button>
    </div>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Employee</th>
          <th>Reviewer</th>
          <th>Cycle</th>
          <th>Score</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="evaluationTable"></tbody>
    </table>
  </main>

  <div class="modal-bg" id="addModal">
    <div class="modal">
      <h2>Add Evaluation</h2>
      <label>Employee</label>
      <input type="text" id="empName" placeholder="Employee Name">

      <label>Reviewer</label>
      <input type="text" id="revName" placeholder="Reviewer Name">

      <label>Cycle</label>
      <input type="text" id="cycle" placeholder="Cycle (e.g. Q1 2025)">

      <label>Score</label>
      <input type="number" id="score" placeholder="Score">

      <label>Status</label>
      <select id="status">
        <option value="Pending">Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
      </select>

      <div class="modal-actions">
        <button id="saveBtn">Save</button>
        <button id="cancelBtn">Cancel</button>
      </div>
    </div>
  </div>

  <script>
    const tableBody = document.getElementById('evaluationTable');
    const addBtn = document.getElementById('addBtn');
    const addModal = document.getElementById('addModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveBtn');
    const searchInput = document.getElementById('searchInput');

    let evaluations = JSON.parse(localStorage.getItem('evaluations') || '[]');

    function renderTable(filter = '') {
      tableBody.innerHTML = '';
      const filtered = evaluations.filter(e =>
        e.employee.toLowerCase().includes(filter.toLowerCase()) ||
        e.reviewer.toLowerCase().includes(filter.toLowerCase())
      );
      if (filtered.length === 0) {
        tableBody.innerHTML = `<tr><td colspan='7' class='no-data'>No evaluations found</td></tr>`;
        return;
      }
      filtered.forEach((e, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${e.employee}</td>
          <td>${e.reviewer}</td>
          <td>${e.cycle}</td>
          <td>${e.score}</td>
          <td><span class='status ${e.status.toLowerCase()}'>${e.status}</span></td>
          <td><button class='remove-btn' onclick='removeEval(${index})'>Remove</button></td>
        `;
        tableBody.appendChild(row);
      });
    }

    function removeEval(index) {
      if (confirm('Remove this evaluation?')) {
        evaluations.splice(index, 1);
        localStorage.setItem('evaluations', JSON.stringify(evaluations));
        renderTable(searchInput.value);
      }
    }

    addBtn.onclick = () => addModal.style.display = 'flex';
    cancelBtn.onclick = () => addModal.style.display = 'none';

    saveBtn.onclick = () => {
      const newEval = {
        employee: document.getElementById('empName').value,
        reviewer: document.getElementById('revName').value,
        cycle: document.getElementById('cycle').value,
        score: document.getElementById('score').value,
        status: document.getElementById('status').value
      };
      if (!newEval.employee || !newEval.reviewer || !newEval.cycle || !newEval.score) {
        alert('Please fill out all fields.');
        return;
      }
      evaluations.push(newEval);
      localStorage.setItem('evaluations', JSON.stringify(evaluations));
      addModal.style.display = 'none';
      renderTable(searchInput.value);
      document.getElementById('empName').value = '';
      document.getElementById('revName').value = '';
      document.getElementById('cycle').value = '';
      document.getElementById('score').value = '';
      document.getElementById('status').value = 'Pending';
    };

    searchInput.oninput = () => renderTable(searchInput.value);

    renderTable();
  </script>
</body>
</html>