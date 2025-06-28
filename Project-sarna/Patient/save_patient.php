<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = ''; // Change if you have a password
$database = 'clinic_db'; // Replace with your actual database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize form data
$patient_name = $conn->real_escape_string($_POST['patient_name']);
$gender = $conn->real_escape_string($_POST['gender']);
$age = (int) $_POST['age'];
$dob = $conn->real_escape_string($_POST['dob']);
$contact = $conn->real_escape_string($_POST['contact']);
$email = $conn->real_escape_string($_POST['email']);
$address = $conn->real_escape_string($_POST['address']);
$blood_group = $conn->real_escape_string($_POST['blood_group']);
$medical_history = $conn->real_escape_string($_POST['medical_history']);

// Insert query
$sql = "INSERT INTO patients (patient_name, gender, age, dob, contact, email, address, blood_group, medical_history)
        VALUES ('$patient_name', '$gender', $age, '$dob', '$contact', '$email', '$address', '$blood_group', '$medical_history')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Patient data saved successfully!'); window.location.href='view_patient.php';</script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
