<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die(json_encode(['error' => "Connection failed: " . $conn->connect_error]));
}

// Get medicines
$medicines = [];
$res = $conn->query("SELECT DISTINCT name FROM medicines ORDER BY name LIMIT 100");
while ($row = $res->fetch_assoc()) {
    $medicines[] = $row['name'];
}

// Get diagnoses
$diagnoses = [];
$res = $conn->query("SELECT DISTINCT diagnosis FROM prescriptions ORDER BY diagnosis LIMIT 100");
while ($row = $res->fetch_assoc()) {
    $diagnoses[] = $row['diagnosis'];
}

// Get patient names
$patients = [];
$res = $conn->query("SELECT DISTINCT patient_name FROM prescriptions ORDER BY patient_name LIMIT 100");
while ($row = $res->fetch_assoc()) {
    $patients[] = $row['patient_name'];
}

header('Content-Type: application/json');
echo json_encode([
    'medicines' => $medicines,
    'diagnoses' => $diagnoses,
    'patients' => $patients
]);

$conn->close();
