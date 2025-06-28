<?php
$conn = new mysqli('localhost', 'root', '', 'clinic_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$id = $_POST['id'];
$name = $conn->real_escape_string($_POST['patient_name']);
$gender = $conn->real_escape_string($_POST['gender']);
$age = (int) $_POST['age'];
$contact = $conn->real_escape_string($_POST['contact']);
$address = $conn->real_escape_string($_POST['address']);

$sql = "UPDATE patients SET 
          patient_name='$name', 
          gender='$gender', 
          age=$age, 
          contact='$contact', 
          address='$address' 
        WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Patient updated successfully'); window.location.href='view_patients.php';</script>";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
