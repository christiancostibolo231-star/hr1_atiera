<?php
date_default_timezone_set('Asia/Manila');
$admin_name = "James Kneechtel DL. Sabandal";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atiera Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #b89146;
            --secondary-color: #a77a2f;
            --dark-blue: #1f2d3d;
            --light-gray: #f5f6fa;
            --white: #ffffff;
            --text-color: #2d2d2d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light-gray);
            color: var(--text-color);
            padding: 30px 40px;
        }

        h2.section-title {
            color: var(--dark-blue);
            font-weight: 700;
            margin-bottom: 20px;
        }

        /* Summary Cards */
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }
        .card {
    background: #ffffff;
    border-radius: 16px;
    padding: 25px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}
.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    background: linear-gradient(180deg, #fffef8, #f7f3e9);
}
.card:hover .card-title,
.card:hover .card-value {
    color: #b89146; /* 👉 gold text color on hover */
    transition: color 0.3s ease-in-out;
}
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .card-title {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
        }
        .card-value {
            font-size: 30px;
            font-weight: 700;
            color: #1f2d3d;
        }
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
        }
        .card-blue { background: #004AAD; }
        .card-gold { background: #b89146; }
        .card-dark { background: #1f2d3d; }
        .card-gray { background: #6c757d; }

        /* Analytics Section */
        .analytics-section {
            background: var(--white);
            border-radius: 14px;
            padding: 25px 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-top: 40px;
        }
        .analytics-section h3 {
            color: var(--dark-blue);
            font-weight: 700;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: var(--primary-color);
            color: #fff;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: rgba(184,145,70,0.05);
        }

        .status {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
            text-align: center;
            min-width: 90px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .status-hired {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Chart Grid */
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }
        .chart-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        .chart-card h4 {
            margin-bottom: 10px;
            color: var(--dark-blue);
            font-weight: 600;
        }
    </style>
</head>

<body>

<?php
ob_start();
?>

    <h2 class="section-title">HR Dashboard Overview</h2>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Total Job Posts</div>
                <div class="card-icon card-blue"><i class="fas fa-briefcase"></i></div>
            </div>
            <div class="card-value">24</div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Applicants Submitted</div>
                <div class="card-icon card-gold"><i class="fas fa-users"></i></div>
            </div>
            <div class="card-value">187</div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Interviews Scheduled</div>
                <div class="card-icon card-dark"><i class="fas fa-calendar-check"></i></div>
            </div>
            <div class="card-value">42</div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Hired Employees</div>
                <div class="card-icon card-gold"><i class="fas fa-user-check"></i></div>
            </div>
            <div class="card-value">18</div>
        </div>

        <!-- ✅ Added cards -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Pending Applications</div>
                <div class="card-icon card-gray"><i class="fas fa-clock"></i></div>
            </div>
            <div class="card-value">63</div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Rejected Applications</div>
                <div class="card-icon card-gray"><i class="fas fa-times-circle"></i></div>
            </div>
            <div class="card-value">32</div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Departments</div>
                <div class="card-icon card-blue"><i class="fas fa-building"></i></div>
            </div>
            <div class="card-value">8</div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">User Accounts</div>
                <div class="card-icon card-dark"><i class="fas fa-user-friends"></i></div>
            </div>
            <div class="card-value">215</div>
        </div>
    </div>

    <!-- Rest of content unchanged -->
    <div class="analytics-section">
        <h3>Recent Applicant Activity</h3>
        <table>
            <thead>
                <tr>
                    <th>Applicant Name</th>
                    <th>Position Applied</th>
                    <th>Department</th>
                    <th>Date Applied</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Maria Santos</td>
                    <td>Front Office Manager</td>
                    <td>Operations</td>
                    <td>Oct 27, 2025</td>
                    <td><span class="status status-pending">Pending</span></td>
                </tr>
                <tr>
                    <td>James Reyes</td>
                    <td>Executive Chef</td>
                    <td>Culinary</td>
                    <td>Oct 26, 2025</td>
                    <td><span class="status status-hired">Hired</span></td>
                </tr>
                <tr>
                    <td>Anna Lim</td>
                    <td>Sommelier</td>
                    <td>Beverage</td>
                    <td>Oct 26, 2025</td>
                    <td><span class="status status-rejected">Rejected</span></td>
                </tr>
                <tr>
                    <td>Carlos Cruz</td>
                    <td>Spa Therapist</td>
                    <td>Wellness</td>
                    <td>Oct 25, 2025</td>
                    <td><span class="status status-pending">Pending</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="analytics-section" style="margin-top:40px;">
            <h3 class="section-title">Applicant Trends and Patterns</h3>
            <div class="chart-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:30px;margin-top:20px;">
                
                <div class="chart-card">
                    <h4>Monthly Applicants</h4>
                    <canvas id="monthlyApplicants"></canvas>
                </div>

                <div class="chart-card">
                    <h4>Job Post Popularity</h4>
                    <canvas id="jobPostPopularity"></canvas>
                </div>

                <div class="chart-card">
                    <h4>Application Status Breakdown</h4>
                    <canvas id="statusBreakdown"></canvas>
                </div>

                <div class="chart-card">
                    <h4>Gender Distribution</h4>
                    <canvas id="genderDistribution"></canvas>
                </div>

                <div class="chart-card">
                    <h4>Department Hiring Trends</h4>
                    <canvas id="departmentTrends"></canvas>
                </div>
            </div>
        </div>



<?php
$content = ob_get_clean();
include 'admin_navbar-sidebar.php';
?>

</body>
</html>
