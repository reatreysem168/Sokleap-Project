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
  /* Reset some basic elements */
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f7f9fc;
    margin: 40px auto;
    max-width: 1200px;
    color: #333;
  }

  h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #2c3e50;
  }

  table {
    border-collapse: collapse;
    width: 100%;
    background: #fff;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
    border-radius: 8px;
    overflow: hidden;
  }

  thead {
    background: #2c3e50;
    color: #fff;
  }

  thead th {
    padding: 12px 15px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #e1e8ed;
    font-size: 14px;
  }

  tbody tr:hover {
    background-color: #f1f6fb;
    cursor: default;
  }

  .actions a {
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 13px;
    margin-right: 8px;
    transition: background-color 0.3s ease;
  }

  .actions a:hover {
    opacity: 0.9;
  }

  .actions a:first-child {
    background-color: #3498db;
    color: white;
  }

  .actions a.delete {
    background-color: #e74c3c;
    color: white;
  }

  /* Responsive */
  @media (max-width: 900px) {
    table, thead, tbody, th, td, tr {
      display: block;
    }
    thead tr {
      display: none;
    }
    tbody tr {
      margin-bottom: 20px;
      border-radius: 8px;
      background: white;
      padding: 15px;
      box-shadow: 0 1px 5px rgb(0 0 0 / 0.1);
    }
    tbody td {
      padding-left: 50%;
      position: relative;
      text-align: left;
      border: none;
      border-bottom: 1px solid #eee;
    }
    tbody td:before {
      content: attr(data-label);
      position: absolute;
      left: 15px;
      font-weight: 700;
      color: #555;
      white-space: nowrap;
    }
    .actions a {
      margin: 0 5px 10px 0;
      display: inline-block;
    }
  }
</style>
</head>
<body>

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
                    <a href="edit.php?id=<?= htmlspecialchars($row['prescription_id'] ?? '') ?>">Edit</a>
                    <a class="delete" href="delete.php?id=<?= htmlspecialchars($row['prescription_id'] ?? '') ?>" onclick="return confirm('Are you sure you want to delete this prescription?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="12" style="text-align:center; padding: 20px;">No records found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
git