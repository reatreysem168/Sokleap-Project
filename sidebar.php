<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SLM1 Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
<div class="sticky top-0  overflow-y-auto ">

    <!-- Sidebar -->
    <nav class="fixed top-0 left-0 h-screen w-64 bg-gray-800 text-white overflow-y-auto">
        <div>
            <h4 class="text-center text-yellow-400 font-bold text-xl mb-6">SLM1</h4>
            <ul class="space-y-2">
                <?php
                $menu_items = [
                    [
                        'url' => 'Manage Page/index.php',
                        'icon' => 'fa-cogs',
                        'text' => 'Admin Page'
                    ],
                    [
                        'url' => 'prescription_form.php',
                        'icon' => 'fa-prescription-bottle-alt',
                        'text' => 'Prescription'
                    ],
                    [
                        'url' => 'print_prescription.php',
                        'icon' => 'fa-file-medical-alt',
                        'text' => '& Invoice'
                    ],
                    [
                        'url' => 'doctor_info.php',
                        'icon' => 'fa-user-md',
                        'text' => 'Input Doctor Info'
                    ],
                    [
                        'url' => 'medicine.php',
                        'icon' => 'fa-pills',
                        'text' => 'Medicine Input'
                    ],
                    [
                        'url' => 'insert_diagnosis.php',
                        'icon' => 'fa-notes-medical',
                        'text' => 'Add Diagnosis'
                    ],
                    [
                        'url' => 'staff_list.php',
                        'icon' => 'fa-notes-medical',
                        'text' => 'View Staff'
                    ],
                    [
                        'url' => 'report.php',
                        'icon' => 'fa-chart-line',
                        'text' => 'Reports'
                    ]
                ];

                foreach ($menu_items as $item) {
                    $is_active = ($current_page === basename($item['url'])) ? 'bg-teal-500' : 'hover:bg-teal-500';
                    echo '<li>
                        <a href="'.$item['url'].'" class="flex items-center gap-3 px-4 py-2 rounded transition '.$is_active.'">
                            <i class="fas '.$item['icon'].'"></i> '.$item['text'].'
                        </a>
                    </li>';
                }
                ?>
            </ul>
        </div>
        <div class="border-t border-gray-500 pt-4">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-2 hover:bg-red-600 rounded transition">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

</div>
</body>
</html>