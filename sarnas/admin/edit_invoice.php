<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$invoice_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receive_by = $_POST['receive_by'];
    $total_amount = $_POST['total_amount'];

    $stmt = $conn->prepare("UPDATE invoices SET receive_by = ?, total_amount = ? WHERE id = ?");
    $stmt->bind_param("sii", $receive_by, $total_amount, $invoice_id);

    if ($stmt->execute()) {
        $message = "Invoice updated successfully.";
    } else {
        $message = "Error updating invoice.";
    }

    $stmt->close();
    $conn->close();

    header("Location: dashboard.php?tab=invoices&message=" . urlencode($message));
    exit();
}

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

if (!$invoice) {
    echo "Invoice not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">Edit Invoice</h2>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Received By:</label>
                <input type="text" name="receive_by" value="<?= htmlspecialchars($invoice['receive_by']) ?>" required
                       class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Total Amount:</label>
                <input type="number" name="total_amount" value="<?= htmlspecialchars($invoice['total_amount']) ?>" required
                       class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400">
            </div>

            <div class="flex justify-end">
                <input type="submit" value="Update Invoice"
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 cursor-pointer">
            </div>
        </form>
    </div>
</body>
</html>
