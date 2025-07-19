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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="container mx-auto max-w-95p my-10 bg-white p-5 rounded-lg shadow-md">
    <h2 class="text-center text-gray-700 mb-8 text-2xl font-semibold">Manage Prescriptions</h2>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-700 text-white">
            <tr>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">ID</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Patient Name</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Age</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Gender</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Diagnosis</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Doctor</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Date</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Received By</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Total Amount</th>
                <th class="px-3 py-3 text-left text-xs uppercase tracking-wider">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-200 hover:bg-blue-50 transition-colors">
                        <td class="px-3 py-3 text-sm" data-label="ID"><?= htmlspecialchars($row['prescription_id'] ?? '') ?></td>
                        <td class="px-3 py-3 text-sm" data-label="Patient Name"><?= htmlspecialchars($row['patient_name'] ?? '') ?></td>
                        <td class="px-3 py-3 text-sm" data-label="Age"><?= htmlspecialchars($row['age'] ?? '') ?></td>
                        <td class="px-3 py-3 text-sm" data-label="Gender"><?= htmlspecialchars($row['gender'] ?? '') ?></td>
                        <td class="px-3 py-3 text-sm" data-label="Diagnosis"><?= htmlspecialchars($row['diagnosis'] ?? '') ?></td>
                        <td class="px-3 py-3 text-sm" data-label="Doctor"><?= htmlspecialchars($row['doctor_name'] ?? '') ?></td>
                        <td class="px-3 py-3 text-sm" data-label="Date"><?= htmlspecialchars($row['date'] ?? '') ?></td>
                        <td class="px-3 py-3 text-sm" data-label="Received By"><?= htmlspecialchars($row['receive_by'] ?? '') ?></td>
                        <td class="px-3 py-3 text-sm" data-label="Total Amount"><?= number_format($row['total_amount'] ?? 0) ?> ៛</td>
                        <td class="px-3 py-3 text-sm" data-label="Actions">
                            <div class="flex flex-wrap gap-2">
                                <a href="print_prescription.php" class="px-2 py-1 text-sm rounded bg-gray-200 hover:bg-gray-300 transition-colors">🖨️ Print</a>
                                <a href="edit.php?id=<?= htmlspecialchars($row['prescription_id'] ?? '') ?>" class="px-2 py-1 text-sm rounded text-white bg-blue-500 hover:bg-blue-600 transition-colors">Edit</a>
                                <a href="delete.php?id=<?= htmlspecialchars($row['prescription_id'] ?? '') ?>" onclick="return confirm('Are you sure you want to delete this prescription?');" class="px-2 py-1 text-sm rounded text-white bg-red-500 hover:bg-red-600 transition-colors">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12" class="text-center py-5">No records found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile responsive styles -->
<style>
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

        .flex {
            justify-content: start;
        }
    }
</style>

</body>
</html>
