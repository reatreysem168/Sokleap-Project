<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SLM1 Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
  <style>
    body {
      background-color: #eef1f4;
      font-family: "Segoe UI", sans-serif;
      margin: 0;
      overflow-x: hidden;
    }

    .container-fluid {
      display: flex;
      flex-wrap: nowrap;
      height: 100vh;
    }

    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, #2c3e50, #34495e);
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow-y: auto;
      padding: 20px 15px;
      scrollbar-width: thin;
      scrollbar-color: #1abc9c #2c3e50;
    }

    .sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar::-webkit-scrollbar-thumb {
      background-color: #1abc9c;
      border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-track {
      background-color: #2c3e50;
    }

    .sidebar h4 {
      font-weight: bold;
      text-align: center;
      margin-bottom: 20px;
      color: #f1c40f;
    }

    .sidebar a {
      color: #ecf0f1;
      text-decoration: none;
      padding: 12px 18px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
      border-radius: 5px;
      font-size: 15px;
    }

    .sidebar a:hover {
      background-color: #1abc9c;
      color: white;
      padding-left: 24px;
    }

    .logout-link {
      border-top: 1px solid #7f8c8d;
      margin-top: 10px;
      padding-top: 10px;
    }

    main {
      flex-grow: 1;
      overflow-y: auto;
      padding: 30px;
    }

    .card {
      border-radius: 16px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.2s, box-shadow 0.2s;
      background-color: white;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    .card h5 {
      font-size: 1.1rem;
      font-weight: 600;
    }

    .dashboard-title {
      font-weight: 700;
      color: #2c3e50;
      border-bottom: 2px solid #dee2e6;
      padding-bottom: 10px;
      margin-bottom: 30px;
    }

    .report-table th {
      background-color: #2c3e50;
      color: white;
    }

    .report-table td, .report-table th {
      vertical-align: middle;
    }

    .report-table tbody tr:hover {
      background-color: #f9f9f9;
    }

    .fa {
      font-size: 1.1rem;
    }

    @media (max-width: 768px) {
      .container-fluid {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        height: auto;
        max-height: 200px;
      }
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <!-- Sidebar -->
    <nav class="sidebar">
      <div>
        <h4>SLM1</h4>
        <a href="#"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="admin/dashboard.php"><i class="fas fa-database"></i> Management DB</a>
        <a href="Manage Page/index.php"><i class="fas fa-cogs"></i> Admin Page</a>
        <a href="prescription_form.php"><i class="fas fa-prescription-bottle-alt"></i> Prescription</a>
        <a href="print_prescription.php"><i class="fas fa-file-medical-alt"></i> Prescription & Invoice</a>
        <a href="Patient/input_patient_info.php"><i class="fas fa-user-plus"></i> Input Patient Info</a>
        <a href="Doctor_info/doctor_info.php"><i class="fas fa-user-md"></i> Input Doctor Info</a>
        <a href="Medicine_info/medicine_info.php"><i class="fas fa-pills"></i> Medicine Input</a>
        <a href="report.php"><i class="fas fa-dollar-sign"></i> Add Receiver Money</a>
        <a href="report.php"><i class="fas fa-capsules"></i> Add Medicine</a>
        <a href="report.php"><i class="fas fa-notes-medical"></i> Add Diagnosis</a>
        <a href="report.php"><i class="fas fa-hand-holding-usd"></i> Add Receiver Money</a>
        <a href="report.php"><i class="fas fa-chart-line"></i> Reports</a>
      </div>
      <div class="logout-link">
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </nav>

    <!-- Main Content -->
    <main>
      <h2 class="dashboard-title">Dashboard</h2>
      <div class="row g-4">
        <div class="col-md-3">
          <div class="card p-3 text-center border-0">
            <h5><i class="fas fa-notes-medical text-primary"></i> Prescription</h5>
            <p class="fs-4 fw-bold text-primary">45</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-3 text-center border-0">
            <h5><i class="fas fa-file-invoice-dollar text-success"></i> Invoice</h5>
            <p class="fs-4 fw-bold text-success">120</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-3 text-center border-0">
            <h5><i class="fas fa-user-edit text-warning"></i> NSSF Entry</h5>
            <p class="fs-4 fw-bold text-warning">30</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-3 text-center border-0">
            <h5><i class="fas fa-camera text-danger"></i> Scan Doc</h5>
            <p class="fs-4 fw-bold text-danger">30</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card p-3 text-center border-0">
            <h5><i class="fas fa-chart-bar text-info"></i> Reports</h5>
            <p class="fs-4 fw-bold text-info">15</p>
          </div>
        </div>
      </div>

      <!-- Reports Table -->
      <h3 class="mt-5 mb-3">Recent Reports</h3>
      <div class="table-responsive">
        <table class="table table-bordered report-table table-striped align-middle">
          <thead>
            <tr>
              <th>Date</th>
              <th>Report Type</th>
              <th>Generated By</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>2025-05-01</td><td>Prescription Report</td><td>Admin</td><td>✅ Completed</td></tr>
            <tr><td>2025-05-02</td><td>Invoice Report</td><td>Admin</td><td>⏳ Pending</td></tr>
            <tr><td>2025-05-03</td><td>NSSF Data Entry</td><td>Admin</td><td>✅ Completed</td></tr>
            <tr><td>2025-05-04</td><td>Scan Document</td><td>Admin</td><td>✅ Completed</td></tr>
            <tr><td>2025-05-05</td><td>Reports Summary</td><td>Admin</td><td>⏳ Pending</td></tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
