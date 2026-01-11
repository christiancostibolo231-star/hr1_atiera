<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Newly Applicants</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
    }

    .applicants-container {
      background: #fff;
      border-radius: 14px;
      padding: 28px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      width: 95%;
      max-width: 1150px;
      margin: 30px auto;
      animation: fadeIn 0.6s ease;
    }

    h2 {
      color: #004AAD;
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 10px;
    }

    p.description {
      color: #555;
      font-size: 14.5px;
      margin-bottom: 16px;
    }

    .table-wrapper {
      overflow-x: auto;
    }

    .applicants-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14.5px;
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

    tr:hover {
      background: #f9fafc;
    }

    .btn-view, .btn-read {
      border: none;
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-view {
      background: #d1ecf1;
      color: #0c5460;
      border: 1px solid #bee5eb;
    }

    .btn-view:hover {
      background: #bee5eb;
    }

    .btn-read {
      background: #004AAD;
      color: #fff;
      margin-left: 5px;
    }

    .btn-read:hover {
      background: #00337a;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      background-color: #fff;
      border-radius: 12px;
      width: 500px;
      padding: 25px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      animation: fadeIn 0.3s ease;
    }

    .modal h3 {
      color: #004AAD;
      margin-bottom: 15px;
      text-align: center;
    }

    .modal .info {
      margin-bottom: 8px;
      font-size: 14.5px;
    }

    .modal .info strong {
      color: #333;
    }

    .modal-footer {
      display: flex;
      justify-content: flex-end;
      margin-top: 20px;
    }

    .btn-cancel {
      background: #dc3545;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 8px 14px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-cancel:hover {
      background: #c82333;
    }

    .btn-view-resume {
      background: #004AAD;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 13px;
      cursor: pointer;
    }

    .btn-view-resume:hover {
      background: #00337a;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* AI Summary Modal */
    #aiModal .modal-content {
      width: 550px;
    }

    #ai-summary {
      background: #f1f3f5;
      padding: 12px;
      border-radius: 8px;
      margin-top: 12px;
      font-size: 14px;
      color: #333;
      white-space: pre-line;
    }
  </style>
</head>
<body>
<?php ob_start(); ?>

<div class="applicants-container">
  <h2>Newly Applicants</h2>
  <p class="description">List of applicants who have recently submitted their applications.</p>

  <div class="table-wrapper">
    <table class="applicants-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Job Position</th>
          <th>Date & Time Submitted</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>001</td>
          <td>Maria Santos</td>
          <td>Software Engineer</td>
          <td>October 25, 2025 - 10:45 AM</td>
          <td>
            <button class="btn-view" onclick="openModal('Maria','A.','Santos','Software Engineer','Female','1999-05-03','09123456789','N/A','maria@example.com','Quezon City','resume_maria.pdf')">View</button>
            <button class="btn-read" onclick="openAiModal('Maria Santos', 'Software Engineer', true)">Read Resume</button>
          </td>
        </tr>
        <tr>
          <td>002</td>
          <td>Juan Dela Cruz</td>
          <td>Marketing Assistant</td>
          <td>October 26, 2025 - 02:15 PM</td>
          <td>
            <button class="btn-view" onclick="openModal('Juan','D.','Dela Cruz','Marketing Assistant','Male','1998-11-22','09987654321','N/A','juan@example.com','Manila','resume_juan.pdf')">View</button>
            <button class="btn-read" onclick="openAiModal('Juan Dela Cruz', 'Marketing Assistant', true)">Read Resume</button>
          </td>
        </tr>
        <tr>
          <td>003</td>
          <td>Angela Reyes</td>
          <td>Therapist</td>
          <td>October 27, 2025 - 09:30 AM</td>
          <td>
            <button class="btn-view" onclick="openModal('Angela','M.','Reyes','Therapist','Female','2000-03-14','09876543210','N/A','angela@example.com','Pasig City','resume_angela.pdf')">View</button>
            <button class="btn-read" onclick="openAiModal('Angela Reyes', 'Therapist', false)">Read Resume</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Applicant Info Modal -->
<div id="applicantModal" class="modal">
  <div class="modal-content">
    <h3>Applicant Information</h3>
    <div class="modal-body">
      <p class="info"><strong>First Name:</strong> <span id="fname"></span></p>
      <p class="info"><strong>Middle Initial:</strong> <span id="mi"></span></p>
      <p class="info"><strong>Last Name:</strong> <span id="lname"></span></p>
      <p class="info"><strong>Job Position:</strong> <span id="position"></span></p>
      <p class="info"><strong>Gender:</strong> <span id="gender"></span></p>
      <p class="info"><strong>Birthday:</strong> <span id="birthday"></span></p>
      <p class="info"><strong>Phone:</strong> <span id="phone"></span></p>
      <p class="info"><strong>Contact Info:</strong> <span id="contact"></span></p>
      <p class="info"><strong>Email:</strong> <span id="email"></span></p>
      <p class="info"><strong>Address:</strong> <span id="address"></span></p>
      <p class="info"><strong>Resume:</strong> <button class="btn-view-resume" id="resumeBtn" onclick="viewResume()">View Resume</button></p>
    </div>
    <div class="modal-footer">
      <button class="btn-cancel" onclick="closeModal()">Cancel</button>
    </div>
  </div>
</div>
<!-- AI Resume Reader Modal -->
<div id="aiModal" class="modal">
  <div class="modal-content">
    <h3>AI Resume Evaluation</h3>
    <p id="ai-summary">Analyzing resume...</p>
    <div class="modal-footer">
      <button class="btn-cancel" onclick="closeAiModal()">Close</button>
      <button class="btn-read" onclick="openNotifyModal()">Notify Applicant</button>
    </div>
  </div>
</div>

<!-- Notify Applicant Modal -->
<div id="notifyModal" class="modal">
  <div class="modal-content" style="width: 550px;">
    <h3>Notify Applicant</h3>
    <form id="notifyForm" onsubmit="sendNotification(event)">
      <div class="form-group">
        <label>🏢 Company Name</label>
        <input type="text" id="companyName" required placeholder="Enter your company name" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
      </div>
      <div class="form-group" style="margin-top:10px;">
        <label>👤 Sender Name</label>
        <input type="text" id="senderName" required placeholder="Enter your name or position" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;">
      </div>
      <div class="form-group" style="margin-top:10px;">
        <label>📝 Comments</label>
        <textarea id="comments" rows="4" required placeholder="Write your message to the applicant..." style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;"></textarea>
      </div>
      <div class="form-group" style="margin-top:10px;">
        <label>📧 Applicant Email</label>
        <input type="email" id="appEmail" readonly style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;background:#f9f9f9;">
      </div>
      <div class="form-group" style="margin-top:10px;">
        <label>📱 Applicant Phone</label>
        <input type="text" id="appPhone" readonly style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;background:#f9f9f9;">
      </div>
      <div class="modal-footer" style="margin-top:20px;">
        <button type="button" class="btn-cancel" onclick="closeNotifyModal()">Cancel</button>
        <button type="submit" class="btn-read">Send Notification</button>
      </div>
    </form>
  </div>
</div>

<script>
  let currentApplicant = { name: '', email: '', phone: '' };

  function openAiModal(name, position, qualified) {
    const aiModal = document.getElementById("aiModal");
    const aiSummary = document.getElementById("ai-summary");
    aiSummary.innerText = "Analyzing resume of " + name + " for " + position + "...";
    aiModal.style.display = "flex";

    // simulate AI analysis
    setTimeout(() => {
      if (qualified) {
        aiSummary.innerText =
          `✅ Candidate Evaluation: ${name}\n\n• Position Applied: ${position}\n• The applicant shows strong alignment with job requirements.\n• Resume demonstrates relevant skills and experience.\n• Recommendation: Proceed with interview scheduling.`;
      } else {
        aiSummary.innerText =
          `⚠️ Candidate Evaluation: ${name}\n\n• Position Applied: ${position}\n• Based on the resume, the applicant lacks key qualifications for the Therapist role.\n• Strengths observed: good communication skills, customer service experience, and administrative background.\n• Suggested roles: Front Desk Officer, HR Assistant, or Customer Relations Specialist.\n\n💡 Recommendation: Consider alternative positions better suited to the applicant’s skills.`;
      }
    }, 1200);

    // Save applicant info for notify modal
    currentApplicant.name = name;
    if (name.includes("Maria")) { currentApplicant.email = "maria@example.com"; currentApplicant.phone = "09123456789"; }
    else if (name.includes("Juan")) { currentApplicant.email = "juan@example.com"; currentApplicant.phone = "09987654321"; }
    else { currentApplicant.email = "angela@example.com"; currentApplicant.phone = "09876543210"; }
  }

  function openNotifyModal() {
    document.getElementById("appEmail").value = currentApplicant.email;
    document.getElementById("appPhone").value = currentApplicant.phone;
    document.getElementById("notifyModal").style.display = "flex";
  }

  function closeAiModal() {
    document.getElementById("aiModal").style.display = "none";
  }

  function closeNotifyModal() {
    document.getElementById("notifyModal").style.display = "none";
  }

  function sendNotification(event) {
    event.preventDefault();
    const company = document.getElementById("companyName").value;
    const sender = document.getElementById("senderName").value;
    const comments = document.getElementById("comments").value;
    const email = document.getElementById("appEmail").value;
    const phone = document.getElementById("appPhone").value;

    alert(`✅ Notification Sent!\n\nTo: ${currentApplicant.name}\nEmail: ${email}\nPhone: ${phone}\nFrom: ${company} (${sender})\n\nMessage:\n${comments}`);

    closeNotifyModal();
    closeAiModal();
  }

  window.onclick = function(event) {
    const modals = ["applicantModal", "aiModal", "notifyModal"];
    for (let id of modals) {
      const modal = document.getElementById(id);
      if (event.target === modal) modal.style.display = "none";
    }
  }
</script>


<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>
</body>
</html>
