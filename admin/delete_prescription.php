<?php
// delete_prescription.php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete prescription and cascade deletes medicines and invoices
    $stmt = $conn->prepare("DELETE FROM prescriptions WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?msg=Prescription+deleted+successfully");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
