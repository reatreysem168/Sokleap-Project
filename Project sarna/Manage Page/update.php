<?php
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = $_POST['id'];
$patient_name = $conn->real_escape_string($_POST['patient_name']);
$age = (int) $_POST['age'];
$gender = $conn->real_escape_string($_POST['gender']);
$diagnosis = $conn->real_escape_string($_POST['diagnosis']);
$doctor_name = $conn->real_escape_string($_POST['doctor_name']);
$date = $conn->real_escape_string($_POST['date']);

$sql = "UPDATE prescriptions SET 
          patient_name = '$patient_name',
          age = $age,
          gender = '$gender',
          diagnosis = '$diagnosis',
          doctor_name = '$doctor_name',
          date = '$date'
        WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Prescription updated successfully!'); window.location.href='your_list_page.php';</script>";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
