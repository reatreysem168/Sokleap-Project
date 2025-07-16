<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['medicine_name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 1);

    // Validate inputs
    if (empty($name) || $price <= 0 || $quantity <= 0) {
        header('Location: index.php?error=invalid');
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO medicines (name, price, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $quantity]);

        header('Location: index.php?success=1');
        exit();
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry
            header('Location: index.php?error=duplicate');
        } else {
            header('Location: index.php?error=database');
        }
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>
