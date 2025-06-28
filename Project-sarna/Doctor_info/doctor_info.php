<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Doctor Information Form</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
      padding: 20px;
    }
    .form-container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h4 class="mb-4 text-primary">Doctor Registration Form</h4>
    <form action="save_doctor.php" method="POST" enctype="multipart/form-data">
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="full_name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        <div class="col-md-3">
          <label for="gender" class="form-label">Gender</label>
          <select class="form-select" id="gender" name="gender" required>
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="dob" class="form-label">Date of Birth</label>
          <input type="date" class="form-control" id="dob" name="dob">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label for="specialty" class="form-label">Specialty</label>
          <input type="text" class="form-control" id="specialty" name="specialty" placeholder="e.g. Cardiologist" required>
        </div>
        <div class="col-md-4">
          <label for="department" class="form-label">Department</label>
          <input type="text" class="form-control" id="department" name="department" required>
        </div>
        <div class="col-md-4">
          <label for="qualification" class="form-label">Qualification</label>
          <input type="text" class="form-control" id="qualification" name="qualification" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="col-md-3">
          <label for="phone" class="form-label">Phone Number</label>
          <input type="tel" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="col-md-3">
          <label for="experience" class="form-label">Experience (years)</label>
          <input type="number" class="form-control" id="experience" name="experience" min="0">
        </div>
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Home Address</label>
        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="profile_pic" class="form-label">Profile Picture</label>
          <input type="file" class="form-control" id="profile_pic" name="profile_pic" accept="image/*">
        </div>
        <div class="col-md-6">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" id="status" name="status" required>
            <option value="Active">Active</option>
            <option value="On Leave">On Leave</option>
            <option value="Retired">Retired</option>
          </select>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary">Save Doctor</button>
        <button type="reset" class="btn btn-secondary">Clear</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
