<?php
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
SELECT 
  p.id AS prescription_id,
  p.patient_name,
  p.age,
  p.gender,
  p.diagnosis,
  p.doctor_name,
  p.date,
  GROUP_CONCAT(DISTINCT CONCAT(
    m.name, ': ', m.quantity, ' (',
    COALESCE(m.morning, ''), '-', 
    COALESCE(m.afternoon, ''), '-', 
    COALESCE(m.evening, ''), '-', 
    COALESCE(m.night, ''), ') - ', 
    COALESCE(m.instructions, '')
  ) SEPARATOR '; ') AS medicines,
  MAX(inv.receive_by) AS receive_by,
  MAX(inv.total_amount) AS total_amount,
  GROUP_CONCAT(DISTINCT CONCAT(mp.name, ': ', mp.price) SEPARATOR ', ') AS medicine_prices
FROM prescriptions p
LEFT JOIN medicines m ON p.id = m.prescription_id
LEFT JOIN invoices inv ON p.id = inv.prescription_id
LEFT JOIN medicine_prices mp ON m.name = mp.name
GROUP BY p.id, p.patient_name, p.age, p.gender, p.diagnosis, p.doctor_name, p.date
ORDER BY p.date DESC
";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Prescriptions</title>
  <style>
    :root {
      --primary: #2d98da;
      --danger: #e74c3c;
      --background: #f4f7fc;
      --text-color: #333;
      --card-bg: #ffffff;
      --header-bg: #34495e;
    }

    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--background);
      color: var(--text-color);
    }

    .container {
      max-width: 95%;
      margin: 40px auto;
      background-color: var(--card-bg);
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    h2 {
      text-align: center;
      color: var(--header-bg);
      margin-bottom: 30px;
      font-size: 28px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      overflow-x: auto;
    }

    thead {
      background-color: var(--header-bg);
      color: #fff;
    }

    thead th {
      padding: 12px;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 0.03em;
      text-align: left;
    }

    tbody tr {
      border-bottom: 1px solid #e1e8ed;
      transition: background 0.3s;
    }

    tbody tr:hover {
      background-color: #ecf5fc;
    }

    tbody td {
      padding: 12px;
      font-size: 14px;
      vertical-align: top;
    }

    .actions {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .actions a {
      padding: 6px 10px;
      font-size: 13px;
      text-decoration: none;
      border-radius: 4px;
      transition: all 0.3s ease;
      display: inline-block;
      font-weight: 600;
    }

    .actions a.edit {
      background-color: var(--primary);
      color: #fff;
    }

    .actions a.edit:hover {
      background-color: #1b79b1;
    }

    .actions a.delete {
      background-color: var(--danger);
      color: #fff;
    }

    .actions a.delete:hover {
      background-color: #c0392b;
    }

    @media (max-width: 1000px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      thead tr {
        display: none;
      }

      tbody tr {
        background-color: #fff;
        margin-bottom: 20px;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      }

      tbody td {
        position: relative;
        padding-left: 50%;
        text-align: left;
        border: none;
        border-bottom: 1px solid #eee;
      }

      tbody td:before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        font-weight: 600;
        color: #555;
        white-space: nowrap;
      }

      .actions {
        justify-content: start;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Manage Prescriptions</h2>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Patient Name</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Diagnosis</th>
        <th>Doctor</th>
        <th>Date</th>
        <th>Medicines</th>
        <th>Received By</th>
        <th>Total Amount</th>
        <th>Medicine Prices</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td data-label="ID"><?= htmlspecialchars($row['prescription_id'] ?? '') ?></td>
            <td data-label="Patient Name"><?= htmlspecialchars($row['patient_name'] ?? '') ?></td>
            <td data-label="Age"><?= htmlspecialchars($row['age'] ?? '') ?></td>
            <td data-label="Gender"><?= htmlspecialchars($row['gender'] ?? '') ?></td>
            <td data-label="Diagnosis"><?= htmlspecialchars($row['diagnosis'] ?? '') ?></td>
            <td data-label="Doctor"><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
            <td data-label="Date"><?= htmlspecialchars($row['date'] ?? '') ?></td>
            <td data-label="Medicines"><?= htmlspecialchars($row['medicines'] ?? '') ?></td>
            <td data-label="Received By"><?= htmlspecialchars($row['receive_by'] ?? '') ?></td>
            <td data-label="Total Amount"><?= number_format($row['total_amount'] ?? 0) ?> ៛</td>
            <td data-label="Medicine Prices"><?= htmlspecialchars($row['medicine_prices'] ?? '') ?></td>
            <td data-label="Actions" class="actions">
              <a href="/Project sarna/print_prescription.php">🖨️ Print</a>
              <a class="edit" href="edit.php?id=<?= htmlspecialchars($row['prescription_id'] ?? '') ?>">Edit</a>
              <a class="delete" href="delete.php?id=<?= htmlspecialchars($row['prescription_id'] ?? '') ?>" onclick="return confirm('Are you sure you want to delete this prescription?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="12" style="text-align:center; padding: 20px;">No records found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>
