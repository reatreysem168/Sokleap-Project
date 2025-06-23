<?php
// DB Connection
$host = "localhost";
$db = "clinic_db";
$user = "root";
$pass = ""; // Use your actual password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Retrieve posted form data
$patientName = $_POST['patientName'] ?? '';
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$diagnosis = $_POST['diagnosis'] ?? '';
$doctorName = $_POST['doctorName'] ?? '';
$date = $_POST['date'] ?? '';
$medicinesRaw = $_POST['medicines'] ?? '[]';
$invoiceItemsRaw = $_POST['invoiceItems'] ?? '[]';
$total = $_POST['total'] ?? 0;
$recieve = $_POST['recieve'] ?? '';

$medicines = json_decode($medicinesRaw, true);
if (!is_array($medicines)) $medicines = [];

$invoiceItems = json_decode($invoiceItemsRaw, true);
if (!is_array($invoiceItems)) $invoiceItems = [];

if (empty($date)) {
  $date = date('Y-m-d');
}

// Format Khmer date
function khmerDate($dateStr) {
  $khmerDigits = ['០','១','២','៣','៤','៥','៦','៧','៨','៩'];
  list($year, $month, $day) = explode('-', $dateStr);

  $toKhmer = function($num) use ($khmerDigits) {
    return strtr($num, array_combine(range(0,9), $khmerDigits));
  };

  return 'ថ្ងៃ' . $toKhmer($day) . ' ខែ' . $toKhmer($month) . ' ឆ្នាំ​' . $toKhmer($year);
}
$khmerFormattedDate = khmerDate($date);

// Insert prescription
$stmt = $conn->prepare("INSERT INTO prescriptions (patient_name, age, gender, diagnosis, doctor_name, date) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sissss", $patientName, $age, $gender, $diagnosis, $doctorName, $date);
if (!$stmt->execute()) {
  die("Database error while inserting prescription: " . $stmt->error);
}
$prescriptionId = $stmt->insert_id;
$stmt->close();

// Insert medicines
if (!empty($medicines)) {
  $stmt = $conn->prepare("INSERT INTO medicines (prescription_id, name, morning, afternoon, evening, night, quantity, instructions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  foreach ($medicines as $med) {
    $name = $med['name'] ?? '';
    $morning = $med['morning'] ?? '';
    $afternoon = $med['afternoon'] ?? '';
    $evening = $med['evening'] ?? '';
    $night = $med['night'] ?? '';
    $quantity = $med['quantity'] ?? 0;
    $instructions = $med['instructions'] ?? '';
    $stmt->bind_param("isssssis", $prescriptionId, $name, $morning, $afternoon, $evening, $night, $quantity, $instructions);
    $stmt->execute();
  }
  $stmt->close();
}

// Insert invoice
$stmt = $conn->prepare("INSERT INTO invoices (prescription_id, receive_by, total_amount) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $prescriptionId, $recieve, $total);
$stmt->execute();
$stmt->close();

// ✅ Fetch medicine prices from DB
$medicinePrices = [];
$result = $conn->query("SELECT name, price FROM medicine_prices");
if ($result) {
  while ($row = $result->fetch_assoc()) {
    $medicinePrices[$row['name']] = (int)$row['price'];
  }
}

$conn->close();
?>

<script>
  const medicinePrices = <?= json_encode($medicinePrices, JSON_UNESCAPED_UNICODE) ?>;

  function addToInvoiceFromPrescription(medicineName, quantity) {
    const price = medicinePrices[medicineName] || 0;
    const total = price * quantity;
    const table = document.querySelector("#invoice-table tbody");
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${table.rows.length + 1}</td>
      <td>${medicineName}</td>
      <td>${price.toLocaleString()} ៛</td>
      <td>${quantity}</td>
      <td>${total.toLocaleString()} ៛</td>
    `;
    table.appendChild(row);
    updateInvoiceTotal();
  }

  function updateInvoiceTotal() {
    let total = 0;
    document.querySelectorAll("#invoice-table tbody tr").forEach(row => {
      const value = parseInt(row.cells[4].textContent.replace(/[^\d]/g, ''));
      total += isNaN(value) ? 0 : value;
    });
    document.getElementById("invoice-total").textContent = total.toLocaleString() + " ៛";
  }

  document.addEventListener('DOMContentLoaded', function () {
    const medicinesFromPHP = <?= json_encode($medicines, JSON_UNESCAPED_UNICODE) ?>;
    medicinesFromPHP.forEach(med => {
      const name = med.name || '';
      const quantity = parseInt(med.quantity || 0);
      if (name && quantity > 0) {
        addToInvoiceFromPrescription(name, quantity);
      }
    });
  });
</script>

<!DOCTYPE html>
<html lang="km">
<head>
  <meta charset="UTF-8">
  <title>វេជ្ជបញ្ជា និង វិក្កយបត្រ</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Khmer OS', Arial, sans-serif;
      margin: 0; padding: 0; background: #fff; color: #000;
    }
    @page { size: A4; margin: 10mm 10mm; }
    .btn-print {
      margin: 10px auto; padding: 8px 40px; font-size: 14px;
      background: green; color: white; border: none;
      border-radius: 4px; cursor: pointer; margin-right: 5px;
    }
    .container {
      width: 100%; max-width: 1000px; margin: auto;
      padding: 10px; border: 1px solid #000;
    }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .header img { height: 50px; margin-right: 35px; margin-left: 35px;}
    .clinic-name {
      flex: 1; text-align: center; font-size: 18px; font-family: 'Khmer OS Muol Light';
    }
    hr { margin: 5px 0 10px; }
    h1.title, h2.title {
      font-size: 20px; margin: 5px 0 10px; text-align: center; font-family: 'Khmer OS Muol Light';
    }
    .patient-info p { font-size: 14px; margin: 3px 0; }
    .medicine-table {
      width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px;
    }
    .medicine-table th, .medicine-table td {
      border: 1px solid #000; padding: 4px; text-align: center;
    }
    .footer-rig {
      display: flex; justify-content: space-between; font-size: 14px; margin-top: 10px;
    }
    .footer-r {
      font-size: 14px; text-align: right; margin-top: 10px; font-family: 'Khmer OS Muol Light';
    }
    .footer p { font-size: 13px; margin: 2px 0; }
    .page-break { page-break-before: always; }
    .footer-right{font-size: 14px; font-family: 'Khmer OS Muol Light';}
    footer {
      position: fixed; left: 0; bottom: 0; width: 100%; text-align: center;
    }
    @media print {
      .btn-print { display: none; }
      .container { border: none; padding: 0; }
    }
  </style>
</head>

<body>
  <div style="text-align: right;">
    <button class="btn-print" onclick="window.print()">Print</button>
  </div>

  <!-- Prescription Section -->
  <div class="container">
    <div class="header">
      <img src="pic/left.png" alt="Left Logo">
      <div class="clinic-name">មន្ទីរពហុព្យាបាល សុខ លាភ មេត្រី<br>SOK LEAP METREY POLYCLINIC</div>
      <img src="pic/right.png" alt="Right Logo">
    </div>
    <hr>
    <h1 class="title">វេជ្ជបញ្ជា</h1>
    <section class="patient-info">
      <p><strong>ឈ្មោះ៖</strong> <?= htmlspecialchars($patientName) ?>
        <span style="margin-left: 30px;"><strong>ភេទ:</strong> <?= htmlspecialchars($gender) ?>
        <strong>អាយុ:</strong> <?= htmlspecialchars($age) ?> ឆ្នាំ</span>
      </p>
      <p><strong>រោគវិនិច្ឆ័យ៖</strong> <?= htmlspecialchars($diagnosis) ?></p>
    </section>

    <table class="medicine-table">
      <thead>
        <tr>
          <th>ល.រ</th><th>ឈ្មោះថ្នាំ</th><th>ព្រឹក</th><th>ថ្ងៃ</th><th>ល្ងាច</th><th>យប់</th><th>ចំនួន</th><th>របៀបប្រើប្រាស់</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($medicines as $i => $med): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($med['name'] ?? '') ?></td>
          <td><?= htmlspecialchars($med['morning'] ?? '') ?></td>
          <td><?= htmlspecialchars($med['afternoon'] ?? '') ?></td>
          <td><?= htmlspecialchars($med['evening'] ?? '') ?></td>
          <td><?= htmlspecialchars($med['night'] ?? '') ?></td>
          <td><?= htmlspecialchars($med['quantity'] ?? '') ?></td>
          <td><?= htmlspecialchars($med['instructions'] ?? '') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="footer-rig">
      <div>
        <p><strong>ថ្ងៃណាត់៖</strong> ....................................</p>
        <p>សូមយកវេជ្ជបញ្ជាមកជាមួយ ពេលមកពិនិត្យលើកក្រោយ។</p>
      </div>
      <div class="footer-right">
        <p><strong><?= $khmerFormattedDate ?></strong></p>
        <p style="margin-left: 35px;">គ្រូពេទ្យព្យាបាល</p><br><br>
        <p><strong style="margin-right: 25px;"><?= htmlspecialchars($doctorName) ?></strong></p>
      </div>
    </div>

    <footer>
      <p>អាស័យដ្ឋាន: ផ្ទះលេខ ៤៧ដេ ផ្លូវ ៣៦០, សង្កាត់ បឹងកេងកង១, ខណ្ឌ ចំការមន, ភ្នំពេញ</p>
      <p>ទូរស័ព្ទ: ០២៣ ៦៦៦៦ ២៣៧ / ០១១ ៣៩ ៨៨៨៨</p>
    </footer>
  </div>

  <!-- Invoice Section -->
  <div class="page-break"></div>
  <div class="container">
    <div class="header">
      <img src="pic/left.png" alt="Left Logo">
      <div class="clinic-name">មន្ទីរពហុព្យាបាល សុខ លាភ មេត្រី<br>SOK LEAP METREY POLYCLINIC</div>
      <img src="pic/right.png" alt="Right Logo">
    </div>
    <hr>
    <h2 class="title">វិក្កយបត្រ</h2>
    <section class="patient-info">
      <p><strong>ឈ្មោះ៖</strong> <?= htmlspecialchars($patientName) ?>
        <span style="margin-left: 20px;"><strong>ភេទ:</strong> <?= htmlspecialchars($gender) ?>
        <strong>អាយុ:</strong> <?= htmlspecialchars($age) ?> ឆ្នាំ</span>
      </p>
    </section>

    <table class="medicine-table" id="invoice-table">
      <thead>
        <tr><th>ល.រ</th><th>សេវា/ថ្នាំ</th><th>តម្លៃ</th><th>បរិមាណ</th><th>សរុប</th></tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr>
          <td colspan="4" style="text-align:right;"><strong>សរុប:</strong></td>
          <td><strong id="invoice-total">0 ៛</strong></td>
        </tr>
      </tfoot>
    </table>

    <div class="footer-r">
      <p><strong><?= $khmerFormattedDate ?></strong></p>
      <p style="margin-right: 25px;">អ្នកទទួល៖</p><br><br>
      <p><strong style="margin-right: 15px;"><?= htmlspecialchars($recieve) ?></strong></p>
    </div>

    <footer>
      <p>អាស័យដ្ឋាន: ផ្ទះលេខ ៤៧ដេ ផ្លូវ ៣៦០, សង្កាត់ បឹងកេងកង១, ខណ្ឌ ចំការមន, ភ្នំពេញ</p>
      <p>ទូរស័ព្ទ: ០២៣ ៦៦៦៦ ២៣៧ / ០១១ ៣៩ ៨៨៨៨</p>
    </footer>
  </div>
</body>
</html>
