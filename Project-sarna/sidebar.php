<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SLM1 Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">

    <!-- Sidebar Only -->

    <nav class="w-64 bg-gradient-to-b from-gray-800 to-gray-700 text-white flex flex-col justify-between p-4 overflow-y-auto">
        <div>
            <h4 class="text-center text-yellow-400 font-bold text-xl mb-6">SLM1</h4>
            <ul class="space-y-2">
                <li><a href="#" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-chart-pie"></i> Dashboard</a></li>
                <li><a href="dashboard.php"
                       class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-database"></i> Management DB</a></li>
                <li><a href="Manage Page/index.php"
                       class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-cogs"></i> Admin Page</a></li>
                <li><a href="prescription_form.php"
                       class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-prescription-bottle-alt"></i> Prescription</a></li>
                <li><a href="print_prescription.php"
                       class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-file-medical-alt"></i> & Invoice</a></li>
                <li><a href="Patient/input_patient_info.php"
                       class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-user-plus"></i> Input Patient Info</a></li>
                <li><a href="Doctor_info/doctor_info.php"
                       class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-user-md"></i> Input Doctor Info</a></li>
                <li><a href="Medicine_info/medicine_info.php"
                       class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-pills"></i> Medicine Input</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-dollar-sign"></i> Add Receiver Money</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-capsules"></i> Add Medicine</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-notes-medical"></i> Add Diagnosis</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-hand-holding-usd"></i> Add Receiver Money</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i
                                class="fas fa-chart-line"></i> Reports</a></li>
            </ul>
        </div>
        <div class="border-t border-gray-500 pt-4">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-2 hover:bg-red-600 rounded transition"><i
                        class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

</div>

<!-- Font Awesome -->
<!--<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>-->
</body>
</html>
