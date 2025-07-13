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
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <?php include __DIR__ . '/../sidebar.php'; ?>

    <div class="ml-64 p-8 flex-1 overflow-auto">
        <!-- Print Button -->
        <div class="text-right mb-4 no-print">
            <button onclick="window.print()"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                <i class="fas fa-print mr-2"></i> Print ID Card
            </button>
            <a href="../doctor_info.php"
               class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>

        <!-- ID Card Container -->
        <div class="id-card-container max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden border-2 border-gray-200">
            <!-- Clinic Header -->
            <div class="bg-blue-800 text-white py-4 text-center">
                <img src="../pic/left.png" alt="Clinic Logo" class="h-16 mx-auto">
                <h1 class="text-xl font-bold mt-2">SOKLEAP METREY POLYCLINIC</h1>
                <p class="text-sm">មន្ទីរពហុព្យាបាល និងសម្ភព សុខលាភ មេត្រី</p>
            </div>

            <!-- Staff Photo -->
            <div class="flex justify-center mt-6">
                <div class="w-32 h-40 border-4 border-white rounded-lg shadow-lg overflow-hidden">
                    <?php
                    if (!empty($staff['profile_pic']) && file_exists('../' . $staff['profile_pic'])) {
                        echo '<img src="../' . htmlspecialchars($staff['profile_pic']) . '" 
                             alt="' . htmlspecialchars($staff['full_name']) . '" 
                             class="w-full h-full object-cover">';
                    } else {
                        echo '<div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-gray-400 text-3xl"></i>
                              </div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Staff Information -->
            <div class="p-6 text-center">
                <div class="mb-6">
                    <h2 class="text-xl font-bold">
                        <?php echo ($staff['gender'] === 'Male' ? 'MR.' : 'MS.'); ?>
                        <?php echo htmlspecialchars($staff['full_name']); ?>
                    </h2>
                    <div class="text-sm text-gray-500 mt-1">
                        <?php echo htmlspecialchars($staff['department']); ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-left">
                    <div>
                        <p class="text-sm text-gray-500">ID:</p>
                        <p><?php echo htmlspecialchars($staff['id']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Position:</p>
                        <p><?php echo htmlspecialchars($staff['department'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone:</p>
                        <p><?php echo htmlspecialchars($staff['phone'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email:</p>
                        <p><?php echo htmlspecialchars($staff['email'] ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-100 px-6 py-3 text-center text-xs">
                <p>Valid until: <?php echo date('Y-m-d', strtotime('+1 year')); ?></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>