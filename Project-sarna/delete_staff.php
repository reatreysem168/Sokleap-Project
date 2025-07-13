<?php
global $pdo;
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Security token mismatch'];
        header("Location: staff_list.php");
        exit;
    }

    // Validate ID
    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        $_SESSION['message'] = ['type' => 'error', 'text' => 'Invalid staff ID'];
        header("Location: staff_list.php");
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Get profile picture path first
        $stmt = $pdo->prepare("SELECT profile_pic FROM staff WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $staff = $stmt->fetch();

        // Delete the record
        $deleteStmt = $pdo->prepare("DELETE FROM staff WHERE id = ?");
        $deleteStmt->execute([$_POST['id']]);

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
// If not POST request, redirect
header("Location: staff_list.php");
exit;
