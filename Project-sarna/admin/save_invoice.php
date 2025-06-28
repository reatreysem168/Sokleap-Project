<?php
// Database connection parameters
$host = "localhost";
$db = "clinic_db";
$user = "root";
$pass = "";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Set charset to support utf8mb4
$conn->set_charset('utf8mb4');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $prescription_id = isset($_POST['prescription_id']) ? intval($_POST['prescription_id']) : 0;
    $receive_by = isset($_POST['receive_by']) ? trim($_POST['receive_by']) : '';
    $invoice_data = isset($_POST['invoice_data']) ? json_decode($_POST['invoice_data'], true) : null;

    if (!$prescription_id || !$receive_by || !is_array($invoice_data)) {
        echo "❌ Invalid input data.";
        exit;
    }

    // Calculate total amount with 2 decimal precision
    $total_amount = 0.0;
    foreach ($invoice_data as $item) {
        if (isset($item['price'], $item['qty']) && 
            is_numeric($item['price']) && 
            is_numeric($item['qty'])
        ) {
            $total_amount += floatval($item['price']) * floatval($item['qty']);
        }
    }
    // Round total amount to 2 decimals
    $total_amount = round($total_amount, 2);

    // Prepare SQL statement to insert invoice
    $stmt = $conn->prepare("INSERT INTO invoices (prescription_id, receive_by, total_amount) VALUES (?, ?, ?)");
    if (!$stmt) {
        echo "❌ Prepare failed: " . htmlspecialchars($conn->error);
        exit;
    }

    // Bind parameters (i = int, s = string, s = string for decimal)
    $total_amount_str = number_format($total_amount, 2, '.', ''); // format as string "123.45"
    $stmt->bind_param("iss", $prescription_id, $receive_by, $total_amount_str);

    // Execute statement and output result
    if ($stmt->execute()) {
        $safe_prescription_id = htmlspecialchars($prescription_id, ENT_QUOTES, 'UTF-8');
        echo "✅ Invoice saved successfully.<br>";
        echo "<a href='print_prescription.php?prescription_id={$safe_prescription_id}'>🔙 Back to Prescription</a>";
    } else {
        echo "❌ Error: " . htmlspecialchars($stmt->error, ENT_QUOTES, 'UTF-8');
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

// Close connection (optional, done automatically at script end)
$conn->close();
?>
