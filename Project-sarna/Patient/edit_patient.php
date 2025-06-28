<?php
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get patient ID
$id = $_GET['id'] ?? 0;

// Fetch existing data
$sql = "SELECT * FROM patients WHERE id = $id";
$result = $conn->query($sql);
$patient = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Patient</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Edit Patient Information</h2>
    <form action="update_patient.php" method="POST">
      <input type="hidden" name="id" value="<?= $patient['id'] ?>">
      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input type="text" class="form-control" name="patient_name" value="<?= $patient['patient_name'] ?>" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Gender</label>
          <select class="form-select" name="gender">
            <option <?= $patient['gender']=='Male'?'selected':'' ?>>Male</option>
            <option <?= $patient['gender']=='Female'?'selected':'' ?>>Female</option>
            <option <?= $patient['gender']=='Other'?'selected':'' ?>>Other</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Age</label>
          <input type="number" class="form-control" name="age" value="<?= $patient['age'] ?>" required>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label">Contact</label>
        <input type="text" class="form-control" name="contact" value="<?= $patient['contact'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea class="form-control" name="address"><?= $patient['address'] ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      <a href="view_patients.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
