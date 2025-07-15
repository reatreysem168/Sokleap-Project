<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $id = (int)$_POST['id'];

    try {
        $pdo->beginTransaction();

        // Get profile picture path first
        $stmt = $pdo->prepare("SELECT profile_pic FROM staff WHERE id = ?");
        $stmt->execute([$id]);
        $staff = $stmt->fetch();

        // Delete the record
        $deleteStmt = $pdo->prepare("DELETE FROM staff WHERE id = ?");
        $deleteStmt->execute([$id]);

        // Delete profile picture if exists
        if ($staff && !empty($staff['profile_pic']) && file_exists($staff['profile_pic'])) {
            unlink($staff['profile_pic']);
        }

        $pdo->commit();

        $_SESSION['message'] = ['type' => 'success', 'text' => 'Staff deleted successfully'];
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Database error: ' . $e->getMessage()];
    }

    header("Location: staff_list.php");
    exit;
}

// If not POST or no id, redirect
header("Location: staff_list.php");
exit;
