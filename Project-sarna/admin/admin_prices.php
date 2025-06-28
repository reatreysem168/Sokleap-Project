<?php
$host = "localhost";
$db = "clinic_db";
$user = "root";
$pass = "";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle add/update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {
  $name = trim($_POST['name']);
  $price = intval($_POST['price']);
  $original = trim($_POST['original_name']);

  if ($name && $price >= 0) {
    if ($original && $original !== $name) {
      // Name changed: delete old entry, insert new
      $stmt = $conn->prepare("DELETE FROM medicine_prices WHERE name = ?");
      $stmt->bind_param("s", $original);
      $stmt->execute();
      $stmt->close();

      $stmt = $conn->prepare("INSERT INTO medicine_prices (name, price) VALUES (?, ?)");
      $stmt->bind_param("si", $name, $price);
      $stmt->execute();
      $stmt->close();
    } else {
      // Name unchanged: update or insert
      $stmt = $conn->prepare("REPLACE INTO medicine_prices (name, price) VALUES (?, ?)");
      $stmt->bind_param("si", $name, $price);
      $stmt->execute();
      $stmt->close();
    }
  }
}

// Handle delete
if (isset($_GET['delete'])) {
  $name = $_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM medicine_prices WHERE name = ?");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $stmt->close();
}

$prices = $conn->query("SELECT * FROM medicine_prices ORDER BY name ASC");

$priceData = [];
$res = $conn->query("SELECT name, price FROM medicine_prices");
while ($row = $res->fetch_assoc()) {
  $priceData[$row['name']] = (int)$row['price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Medicine Prices</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9fafb;
      color: #333;
      padding: 30px;
      max-width: 900px;
      margin: auto;
    }

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 30px;
    }

    form {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
      margin-bottom: 20px;
    }

    form input[type="text"],
    form input[type="number"] {
      flex: 1 1 200px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    form button {
      background: #3498db;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    form button:hover {
      background: #2980b9;
    }

    .table-wrapper {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
    }

    thead {
      background-color: #ecf0f1;
      font-weight: bold;
    }

    tr:nth-child(even) {
      background-color: #f4f6f8;
    }

    .delete {
      color: #e74c3c;
      text-decoration: none;
      font-weight: bold;
    }

    .delete:hover {
      text-decoration: underline;
    }

    .edit {
      color: #27ae60;
      text-decoration: none;
      font-weight: bold;
    }

    .edit:hover {
      text-decoration: underline;
    }

    .missing {
      background-color: #ffe6e6 !important;
    }

    @media (max-width: 600px) {
      form {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <h2>Medicine Price Management</h2>

  <form method="POST" id="price-form">
    <input type="hidden" name="original_name" id="original_name">
    <input type="text" name="name" id="name" placeholder="Medicine Name" required>
    <input type="number" name="price" id="price" placeholder="Price in ៛" required>
    <button type="submit" name="save">Save</button>
  </form>

  <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Price (៛)</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $prices->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= number_format($row['price']) ?></td>
            <td>
              <a href="#" class="edit" onclick="editMedicine('<?= htmlspecialchars(addslashes($row['name'])) ?>', <?= $row['price'] ?>); return false;">Edit</a> |
              <a href="?delete=<?= urlencode($row['name']) ?>" class="delete" onclick="return confirm('Delete this price?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    const medicinePrices = <?= json_encode($priceData) ?>;

    function addToInvoiceFromPrescription(medicineName, quantity) {
      const price = medicinePrices[medicineName] || 0;
      const total = price * quantity;
      const table = document.querySelector("#invoice-table tbody");
      const row = document.createElement("tr");
      row.className = price === 0 ? 'missing' : '';
      row.innerHTML = `
        <td>${table.rows.length + 1}</td>
        <td>${medicineName}</td>
        <td>${price.toLocaleString()} ៛</td>
        <td>${quantity}</td>
        <td>${total.toLocaleString()} ៛</td>
      `;
      table.appendChild(row);
      updateInvoiceTotal();

      if (price === 0) {
        alert(`⚠️ Price missing for "${medicineName}"`);
      }
    }

    function updateInvoiceTotal() {
      let total = 0;
      document.querySelectorAll("#invoice-table tbody tr").forEach(row => {
        const value = parseInt(row.cells[4].textContent.replace(/[^\d]/g, ''));
        total += isNaN(value) ? 0 : value;
      });
      document.getElementById("invoice-total").textContent = total.toLocaleString() + " ៛";
    }

    function editMedicine(name, price) {
      document.getElementById('name').value = name;
      document.getElementById('price').value = price;
      document.getElementById('original_name').value = name;
      document.getElementById('name').focus();
    }
  </script>
</body>
</html>
