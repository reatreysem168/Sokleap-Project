<?php
require 'db.php';

if (isset($_GET['id'])) {
    $medicine_id = $_GET['id'];

    // Delete the medicine record
    $stmt = $conn->prepare("DELETE FROM medicines WHERE id = ?");
    $stmt->bind_param("i", $medicine_id);
    if ($stmt->execute()) {
        header("Location: dashboard.php?tab=medicines&message=Medicine deleted successfully");
        exit();
    } else {
        echo "Error deleting medicine.";
    }
    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
