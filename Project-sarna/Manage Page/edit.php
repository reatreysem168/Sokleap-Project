<?php
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$prescription_id = $_GET['id'] ?? 0;

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = $_POST['patient_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $diagnosis = $_POST['diagnosis'];
    $doctor_name = $_POST['doctor_name'];
    $date = $_POST['date'];
    $receive_by = $_POST['receive_by'];
    $total_amount = $_POST['total_amount'];

    // Update prescriptions
    $conn->query("UPDATE prescriptions SET 
      patient_name='$patient_name', age=$age, gender='$gender', 
      diagnosis='$diagnosis', doctor_name='$doctor_name', date='$date' 
      WHERE id=$prescription_id");

    // Update invoice
    $conn->query("UPDATE invoices SET 
      receive_by='$receive_by', total_amount=$total_amount 
      WHERE prescription_id=$prescription_id");

    // Update medicines (delete and re-insert)
    $conn->query("DELETE FROM medicines WHERE prescription_id=$prescription_id");
    if (isset($_POST['med_name'])) {
        foreach ($_POST['med_name'] as $i => $name) {
            $morning = $_POST['morning'][$i];
            $afternoon = $_POST['afternoon'][$i];
            $evening = $_POST['evening'][$i];
            $night = $_POST['night'][$i];
            $qty = $_POST['quantity'][$i];
            $instructions = $_POST['instructions'][$i];

            $stmt = $conn->prepare("INSERT INTO medicines (prescription_id, name, morning, afternoon, evening, night, quantity, instructions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssis", $prescription_id, $name, $morning, $afternoon, $evening, $night, $qty, $instructions);
            $stmt->execute();
        }
    }

    echo "<script>alert('Updated successfully'); window.location='index.php';</script>";
    exit;
}

// Fetch data
$prescription = $conn->query("SELECT * FROM prescriptions WHERE id = $prescription_id")->fetch_assoc();
$invoice = $conn->query("SELECT * FROM invoices WHERE prescription_id = $prescription_id")->fetch_assoc();
$medicines = $conn->query("SELECT * FROM medicines WHERE prescription_id = $prescription_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Prescription</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --primary: #3498db;
      --secondary: #f8f9fa;
      --danger: #e74c3c;
      --text: #2c3e50;
      --bg: #f0f4f8;
      --card: #ffffff;
    }

    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: var(--bg);
      color: var(--text);
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background: var(--card);
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    h2, h3 {
      text-align: center;
      color: var(--primary);
      margin-bottom: 25px;
    }

    form label {
      display: block;
      margin: 12px 0 5px;
      font-weight: 600;
    }

    input[type="text"],
    input[type="number"],
    input[type="date"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background-color: var(--secondary);
      font-size: 15px;
      transition: border 0.3s;
    }

    input:focus, textarea:focus, select:focus {
      border-color: var(--primary);
      outline: none;
    }

    .med-block {
      padding: 15px;
      border: 1px solid #dce3ea;
      border-radius: 8px;
      background-color: #fdfdfd;
      margin-bottom: 20px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    .btn {
      padding: 12px 25px;
      font-size: 16px;
      background-color: var(--primary);
      border: none;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      display: block;
      margin: 30px auto 0;
    }

    .btn:hover {
      background-color: #2980b9;
    }

    @media (max-width: 768px) {
      .container {
        padding: 20px;
      }

      input, textarea, select {
        font-size: 14px;
      }

      h2, h3 {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Edit Prescription</h2>
  <form method="POST">
    <label>Patient Name:</label>
    <input type="text" name="patient_name" value="<?= $prescription['patient_name'] ?>">

    <label>Age:</label>
    <input type="number" name="age" value="<?= $prescription['age'] ?>">

    <label>Gender:</label>
    <select name="gender">
      <option value="Male" <?= $prescription['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
      <option value="Female" <?= $prescription['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
    </select>

    <label>Diagnosis:</label>
    <textarea name="diagnosis"><?= $prescription['diagnosis'] ?></textarea>

    <label>Doctor Name:</label>
    <input type="text" name="doctor_name" value="<?= $prescription['doctor_name'] ?>">

    <label>Date:</label>
    <input type="date" name="date" value="<?= $prescription['date'] ?>">

    <h3>Medicines</h3>
    <div id="medicines">
      <?php while ($m = $medicines->fetch_assoc()): ?>
        <div class="med-block">
          <label>Medicine Name:</label>
          <input type="text" name="med_name[]" value="<?= $m['name'] ?>">

          <label>Morning:</label>
          <input type="text" name="morning[]" value="<?= $m['morning'] ?>">

          <label>Afternoon:</label>
          <input type="text" name="afternoon[]" value="<?= $m['afternoon'] ?>">

          <label>Evening:</label>
          <input type="text" name="evening[]" value="<?= $m['evening'] ?>">

          <label>Night:</label>
          <input type="text" name="night[]" value="<?= $m['night'] ?>">

          <label>Quantity:</label>
          <input type="number" name="quantity[]" value="<?= $m['quantity'] ?>">

          <label>Instructions:</label>
          <textarea name="instructions[]"><?= $m['instructions'] ?></textarea>
        </div>
      <?php endwhile; ?>
    </div>

    <h3>Invoice</h3>
    <label>Received By:</label>
    <input type="text" name="receive_by" value="<?= $invoice['receive_by'] ?>">

    <label>Total Amount:</label>
    <input type="number" name="total_amount" value="<?= $invoice['total_amount'] ?>">

    <button class="btn" type="submit">Update Prescription</button>
  </form>
</div>

</body>
</html>
