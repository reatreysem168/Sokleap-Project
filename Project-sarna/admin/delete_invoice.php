<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$invoice_id = $_GET['id'];

// Prepare and execute the deletion
$stmt = $conn->prepare("DELETE FROM invoices WHERE id = ?");
$stmt->bind_param("i", $invoice_id);

if ($stmt->execute()) {
    $message = "Invoice deleted successfully.";
} else {
    $message = "Error deleting invoice.";
}

$stmt->close();
$conn->close();

// Redirect back to the dashboard
header("Location: dashboard.php?tab=invoices&message=" . urlencode($message));
exit();
?>
