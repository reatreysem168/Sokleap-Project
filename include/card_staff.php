<?php
require '../db_connect.php';

// Validate staff ID
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'No staff ID specified';
    header("Location: ../staff_list.php");
    exit();
}

$staff_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$staff_id) {
    $_SESSION['error'] = 'Invalid staff ID';
    header("Location: ../staff_list.php");
    exit();
}

// Fetch staff data
try {
    $stmt = $pdo->prepare("SELECT * FROM staff WHERE id = ?");
    $stmt->execute([$staff_id]);
    $staff = $stmt->fetch();

    if (!$staff) {
        $_SESSION['error'] = 'Staff member not found';
        header("Location: ../staff_list.php");
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header("Location: ../staff_list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ID Card: <?php echo htmlspecialchars($staff['full_name']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .font-khmuol {
            font-family: 'Nokora', 'Khmer OS Muol Light', serif;
            font-size: 12px;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .id-card-container, .id-card-container * {
                visibility: visible;
            }
            .id-card-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<!-- Keep your PHP code here... -->

<body class="bg-gray-100 flex min-h-screen">

<?php include __DIR__ . '/../sidebar.php'; ?>

<div class="ml-64 flex items-center justify-center flex-1 p-8">

    <div class="w-[320px] h-[500px] bg-white border-[6px] border-blue-800 rounded-xl shadow-md p-2 ring-2 ring-blue-400 overflow-hidden">

        <div class="text-center">
            <div class="flex justify-center mb-1">
                <img src="../pic/left.png" alt="Clinic Logo"  />
            </div>
            <p class="text-[12px] leading-tight font-khmuol text-blue-900">
                មន្ទីពេទ្យពហុព្យាបាល សុខលាភ មេត្រី
            </p>
            <p class="text-[11px] text-blue-900 font-semibold uppercase">
                SOK LEAP METREY POLYCLINIC AND MATERNITY
            </p>
        </div>

        <div class="bg-blue-800 text-white text-center py-[4px] my-2 rounded">
            <span class="text-[14px] font-khmuol">បណ្ណសម្គាល់បុគ្គលិក</span>
        </div>

        <div class="flex justify-center mt-1 mb-2">
            <?php if (!empty($staff['profile_pic']) && file_exists('../' . $staff['profile_pic'])): ?>
                <img src="../<?= htmlspecialchars($staff['profile_pic']) ?>" alt="<?= htmlspecialchars($staff['full_name']) ?>"
                     class="w-[160px] h-[200px] object-cover border border-gray-500" />
            <?php else: ?>
                <div class="w-[160px] h-[200px] flex items-center justify-center bg-gray-200 border border-gray-500">
                    <i class="fas fa-user text-gray-400 text-4xl"></i>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center text-sm leading-snug">
            <p class="font-khmuol text-blue-900 text-[18px]">
                ឈ្មោះ៖ <?= htmlspecialchars($staff['full_name']) ?>
            </p>
            <p class="text-[17px] text-blue-800 font-medium">
                <?= ($staff['gender'] === 'Male' ? 'Mr.' : 'Ms.') ?> <?= htmlspecialchars($staff['full_name']) ?>
            </p>
            <p class="text-[17px] mt-1 font-khmuol text-blue-800">
                តួនាទី៖ <?= htmlspecialchars($staff['department']) ?>
            </p>
        </div>

    </div>
</div>
</body>
</html>