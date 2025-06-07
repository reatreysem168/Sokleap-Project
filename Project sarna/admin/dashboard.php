<?php
// dashboard.php
require 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Clinic Sok Leap Metrey</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9fafb;
      color: #333;
      margin: 0;
      padding: 30px;
      max-width: 1200px;
      margin-left: auto;
      margin-right: auto;
    }

    h1 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 40px;
    }

    .tab {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    .tab button {
      background: #ecf0f1;
      border: none;
      padding: 12px 20px;
      margin: 5px;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .tab button:hover {
      background-color: #d0d7de;
    }

    .tab button.active-tab {
      background-color: #3498db;
      color: white;
      font-weight: bold;
    }

    .tabcontent {
      display: none;
      animation: fadeIn 0.3s ease-in;
    }

    .tabcontent.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      margin-bottom: 40px;
    }

    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #f0f0f0;
      text-align: left;
    }

    th {
      background-color: #f3f4f6;
      font-weight: bold;
    }

    tr:hover {
      background-color: #f9f9f9;
    }

    a {
      color: #3498db;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      th {
        background: none;
        font-size: 14px;
        padding-top: 10px;
      }

      td {
        padding-left: 50%;
        position: relative;
      }

      td::before {
        position: absolute;
        top: 12px;
        left: 15px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: bold;
      }

      tr {
        margin-bottom: 20px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.1);
        border-radius: 8px;
        background: white;
      }

      td:nth-of-type(1)::before { content: "ID"; }
      td:nth-of-type(2)::before { content: "Prescription ID"; }
      td:nth-of-type(3)::before { content: "Name / Patient"; }
      td:nth-of-type(4)::before { content: "Morning"; }
      td:nth-of-type(5)::before { content: "Afternoon"; }
      td:nth-of-type(6)::before { content: "Evening"; }
      td:nth-of-type(7)::before { content: "Night"; }
      td:nth-of-type(8)::before { content: "Quantity / Age"; }
      td:nth-of-type(9)::before { content: "Instructions / Gender"; }
      td:nth-of-type(10)::before { content: "Doctor / Actions"; }
    }
  </style>
  <script>
    function openTab(tabName) {
      const contents = document.getElementsByClassName("tabcontent");
      const buttons = document.querySelectorAll(".tab button");
      for (let content of contents) content.classList.remove("active");
      for (let btn of buttons) btn.classList.remove("active-tab");
      document.getElementById(tabName).classList.add("active");
      document.querySelector(`[onclick="openTab('${tabName}')"]`).classList.add("active-tab");
    }

    window.onload = () => openTab('Prescriptions');
  </script>
</head>
<body>

<h1>Dashboard - Clinic Sok Leap Metrey</h1>

<div class="tab">
  <button onclick="openTab('Prescriptions')">Prescriptions</button>
  <button onclick="openTab('Medicines')">Medicines</button>
  <button onclick="openTab('Invoices')">Invoices</button>
</div>

<div id="Prescriptions" class="tabcontent">
  <h2>Prescriptions</h2>
  <table>
    <tr>
      <th>ID</th><th>Patient Name</th><th>Age</th><th>Gender</th><th>Diagnosis</th><th>Doctor</th><th>Date</th><th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM prescriptions");
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['patient_name'] . '</td>
            <td>' . $row['age'] . '</td>
            <td>' . $row['gender'] . '</td>
            <td>' . $row['diagnosis'] . '</td>
            <td>' . $row['doctor_name'] . '</td>
            <td>' . $row['date'] . '</td>
            <td>
                <a href="edit_prescription.php?id=' . $row['id'] . '">Edit</a> |
                <a href="delete_prescription.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure?\')">Delete</a>
            </td>
        </tr>';
    }
    ?>
  </table>
</div>

<div id="Medicines" class="tabcontent">
  <h2>Medicines</h2>
  <table>
    <tr>
      <th>ID</th><th>Prescription ID</th><th>Name</th><th>Morning</th><th>Afternoon</th><th>Evening</th><th>Night</th><th>Qty</th><th>Instructions</th><th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM medicines");
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['prescription_id'] . '</td>
            <td>' . $row['name'] . '</td>
            <td>' . $row['morning'] . '</td>
            <td>' . $row['afternoon'] . '</td>
            <td>' . $row['evening'] . '</td>
            <td>' . $row['night'] . '</td>
            <td>' . $row['quantity'] . '</td>
            <td>' . $row['instructions'] . '</td>
            <td>
                <a href="edit_medicine.php?id=' . $row['id'] . '">Edit</a> |
                <a href="delete_medicine.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure?\')">Delete</a>
            </td>
        </tr>';
    }
    ?>
  </table>
</div>

<div id="Invoices" class="tabcontent">
  <h2>Invoices</h2>
  <table>
    <tr>
      <th>ID</th><th>Prescription ID</th><th>Receive By</th><th>Total Amount</th><th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM invoices");
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['prescription_id'] . '</td>
            <td>' . $row['receive_by'] . '</td>
            <td>' . number_format($row['total_amount']) . ' ៛</td>
            <td>
                <a href="edit_invoice.php?id=' . $row['id'] . '">Edit</a> |
                <a href="delete_invoice.php?id=' . $row['id'] . '" onclick="return confirm(\'Are you sure?\')">Delete</a>
            </td>
        </tr>';
    }
    ?>
  </table>
</div>

</body>
</html>
