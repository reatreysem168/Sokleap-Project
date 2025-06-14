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
<html>
<head>
    <title>Edit Prescription</title>
    <style>
        label { display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 5px; }
        .med-block { border: 1px solid #ddd; padding: 10px; margin: 10px 0; }
        .btn { padding: 10px 15px; background: #007bff; color: white; border: none; }
    </style>
</head>
<body>

<h2>Edit Prescription</h2>
<form method="POST">
    <label>Patient Name: <input type="text" name="patient_name" value="<?= $prescription['patient_name'] ?>"></label>
    <label>Age: <input type="number" name="age" value="<?= $prescription['age'] ?>"></label>
    <label>Gender: 
        <select name="gender">
            <option value="Male" <?= $prescription['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= $prescription['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
        </select>
    </label>
    <label>Diagnosis: <textarea name="diagnosis"><?= $prescription['diagnosis'] ?></textarea></label>
    <label>Doctor Name: <input type="text" name="doctor_name" value="<?= $prescription['doctor_name'] ?>"></label>
    <label>Date: <input type="date" name="date" value="<?= $prescription['date'] ?>"></label>

    <h3>Medicines</h3>
    <div id="medicines">
    <?php while ($m = $medicines->fetch_assoc()): ?>
        <div class="med-block">
            <label>Name: <input type="text" name="med_name[]" value="<?= $m['name'] ?>"></label>
            <label>Morning: <input type="text" name="morning[]" value="<?= $m['morning'] ?>"></label>
            <label>Afternoon: <input type="text" name="afternoon[]" value="<?= $m['afternoon'] ?>"></label>
            <label>Evening: <input type="text" name="evening[]" value="<?= $m['evening'] ?>"></label>
            <label>Night: <input type="text" name="night[]" value="<?= $m['night'] ?>"></label>
            <label>Quantity: <input type="number" name="quantity[]" value="<?= $m['quantity'] ?>"></label>
            <label>Instructions: <textarea name="instructions[]"><?= $m['instructions'] ?></textarea></label>
        </div>
    <?php endwhile; ?>
    </div>

    <h3>Invoice</h3>
    <label>Received By: <input type="text" name="receive_by" value="<?= $invoice['receive_by'] ?>"></label>
    <label>Total Amount: <input type="number" name="total_amount" value="<?= $invoice['total_amount'] ?>"></label>

    <br><br>
    <button class="btn" type="submit">Update</button>
</form>

</body>
</html>
