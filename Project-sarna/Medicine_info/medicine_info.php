<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Medicine Input Form</title>
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
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <h4 class="mb-4 text-primary">Medicine Information Form</h4>
    <form action="save_medicine.php" method="POST">
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="medicine_name" class="form-label">Medicine Name</label>
          <input type="text" class="form-control" id="medicine_name" name="medicine_name" required>
        </div>
        <div class="col-md-6">
          <label for="category" class="form-label">Category</label>
          <select class="form-select" id="category" name="category" required>
            <option value="">Select Category</option>
            <option value="Tablet">Tablet</option>
            <option value="Capsule">Capsule</option>
            <option value="Injection">Injection</option>
            <option value="Syrup">Syrup</option>
            <option value="Ointment">Ointment</option>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <label for="generic_name" class="form-label">Generic Name</label>
          <input type="text" class="form-control" id="generic_name" name="generic_name">
        </div>
        <div class="col-md-4">
          <label for="brand_name" class="form-label">Brand Name</label>
          <input type="text" class="form-control" id="brand_name" name="brand_name">
        </div>
        <div class="col-md-4">
          <label for="manufacturer" class="form-label">Manufacturer</label>
          <input type="text" class="form-control" id="manufacturer" name="manufacturer">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-3">
          <label for="price" class="form-label">Price ($)</label>
          <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <div class="col-md-3">
          <label for="stock_quantity" class="form-label">Stock Quantity</label>
          <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
        </div>
        <div class="col-md-3">
          <label for="expiry_date" class="form-label">Expiry Date</label>
          <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
        </div>
        <div class="col-md-3">
          <label for="unit" class="form-label">Unit</label>
          <select class="form-select" id="unit" name="unit" required>
            <option value="">Select</option>
            <option value="Box">Box</option>
            <option value="Strip">Strip</option>
            <option value="Bottle">Bottle</option>
            <option value="Vial">Vial</option>
            <option value="Tube">Tube</option>
          </select>
        </div>
      </div>

      <div class="mb-3">
        <label for="usage" class="form-label">Usage / Instructions</label>
        <textarea class="form-control" id="usage" name="usage" rows="3" placeholder="E.g., Take 1 tablet after meal, twice a day"></textarea>
      </div>

      <div class="mb-3">
        <label for="note" class="form-label">Additional Notes</label>
        <textarea class="form-control" id="note" name="note" rows="2"></textarea>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-success">Save Medicine</button>
        <button type="reset" class="btn btn-secondary">Clear</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
