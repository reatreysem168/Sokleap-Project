<?php
require 'db_connect.php';
$medicineList = [];
$doctorList = [];
$cashierList = [];
try {
    $stmt = $pdo->prepare("SELECT full_name FROM staff WHERE department != 'Doctor' ORDER BY full_name ASC");
    $stmt->execute();
    $cashierList = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
try {
    $stmt = $pdo->prepare("SELECT full_name FROM staff WHERE department = 'Doctor' ORDER BY full_name ASC");
    $stmt->execute();
    $doctorList = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
try {
    $stmt = $pdo->query("SELECT name FROM medicine_prices ORDER BY name ASC");
    $medicineList = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
$diagnosisList = [];
try {
    $stmt = $pdo->query("SELECT name FROM diagnoses ORDER BY name ASC");
    $diagnosisList = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>វេជ្ជបញ្ជា (Prescription Form)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        .khmer-font {
            font-family: 'Khmer OS Battambang', 'Khmer OS Battambang', 'Khmer', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <!-- Main Content -->
    <div class="ml-64 p-8 flex-1 p-6 overflow-y-auto bg-gray-50 khmer-font">
       <?php include 'manage_page/index.php' ?>
    </div>
</div>
</body>
</html>
