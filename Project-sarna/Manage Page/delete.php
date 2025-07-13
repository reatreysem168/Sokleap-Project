<?php
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = $_GET['id'] ?? 0;

// First, delete associated medicines and invoices
$conn->query("DELETE FROM medicines WHERE prescription_id = $id");
$conn->query("DELETE FROM invoices WHERE prescription_id = $id");

// Then, delete the prescription
$sql = "DELETE FROM prescriptions WHERE id = $id";
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Prescription deleted successfully.'); window.location.href='index.php';</script>";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
