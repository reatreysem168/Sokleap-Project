<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clinic_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize & collect patient data
$patient_name = $_POST['patientName'] ?? '';
$age = $_POST['age'] ?? 0;
$gender = $_POST['gender'] ?? '';
$diagnosis = $_POST['diagnosis'] ?? '';
$doctor = $_POST['doctorName'] ?? '';
$recieve = $_POST['recieve'] ?? '';
$date = $_POST['date'] ?? date('Y-m-d');

// Insert into prescriptions
$stmt = $conn->prepare("INSERT INTO prescriptions (patient_name, age, gender, diagnosis, doctor_name, recieve, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sisssss", $patient_name, $age, $gender, $diagnosis, $doctor, $recieve, $date);
if (!$stmt->execute()) {
    die("Error saving prescription: " . $stmt->error);
}
$prescription_id = $stmt->insert_id;
$stmt->close();

// Insert medicines
$medicines_json = $_POST['medicines'] ?? '[]';
$medicines = json_decode($medicines_json, true);

$stmt_med = $conn->prepare("INSERT INTO medicines (prescription_id, name, dosage, frequency, duration, quantity, instruction) VALUES (?, ?, ?, ?, ?, ?, ?)");

// For each medicine, we should map your input keys to DB columns accordingly
foreach ($medicines as $med) {
    $name = $med['name'] ?? '';
    // For dosage, frequency, duration: Since your JS collects morning/afternoon/evening/night, you might want to concatenate or store differently.
    // Here I assume you want dosage = morning+afternoon+evening+night joined by commas
    $dosage = trim(implode(',', array_filter([$med['morning'] ?? '', $med['afternoon'] ?? '', $med['evening'] ?? '', $med['night'] ?? ''])), ',');
    $frequency = ''; // You can adjust if needed
    $duration = '';  // You can adjust if needed
    $quantity = $med['quantity'] ?? '';
    $instruction = $med['instructions'] ?? '';

    $stmt_med->bind_param("issssss", $prescription_id, $name, $dosage, $frequency, $duration, $quantity, $instruction);
    if (!$stmt_med->execute()) {
        // Handle error, but continue
        error_log("Failed to save medicine: " . $stmt_med->error);
    }
}

$stmt_med->close();
$conn->close();

// Redirect to print page
header("Location: print_prescription.php?id=" . $prescription_id);
exit;
