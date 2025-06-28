<?php
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = $_GET['id'] ?? 0;
$sql = "DELETE FROM patients WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Patient deleted successfully'); window.location.href='view_patients.php';</script>";
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
