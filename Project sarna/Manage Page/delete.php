<?php
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 0;
$conn->query("DELETE FROM prescriptions WHERE id = $id");

echo "<script>alert('Record deleted successfully'); window.location='index.php';</script>";
exit;
