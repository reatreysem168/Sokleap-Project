<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Patient Registration Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4 text-center">Patient Registration Form</h2>
    <form action="save_patient.php" method="POST">
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="patient_name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="patient_name" name="patient_name" required>
        </div>
        <div class="col-md-3">
          <label for="gender" class="form-label">Gender</label>
          <select class="form-select" id="gender" name="gender" required>
            <option value="">-- Select --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="age" class="form-label">Age</label>
          <input type="number" class="form-control" id="age" name="age" required min="0">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="dob" class="form-label">Date of Birth</label>
          <input type="date" class="form-control" id="dob" name="dob">
        </div>
        <div class="col-md-6">
          <label for="contact" class="form-label">Contact Number</label>
          <input type="tel" class="form-control" id="contact" name="contact" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email (optional)</label>
        <input type="email" class="form-control" id="email" name="email">
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Address</label>
        <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="blood_group" class="form-label">Blood Group</label>
          <select class="form-select" id="blood_group" name="blood_group">
            <option value="">-- Select --</option>
            <option value="A+">A+</option>
            <option value="A−">A−</option>
            <option value="B+">B+</option>
            <option value="B−">B−</option>
            <option value="AB+">AB+</option>
            <option value="AB−">AB−</option>
            <option value="O+">O+</option>
            <option value="O−">O−</option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="medical_history" class="form-label">Medical History (if any)</label>
          <textarea class="form-control" id="medical_history" name="medical_history" rows="2"></textarea>
        </div>
      </div>

      <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-primary">Save Patient</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
