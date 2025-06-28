<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SLM1 Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
<div class="flex h-screen">
    <!-- Sidebar -->
    <nav class="w-64 bg-gradient-to-b from-gray-800 to-gray-700 text-white flex flex-col justify-between p-4 overflow-y-auto">
        <div>
            <h4 class="text-center text-yellow-400 font-bold text-xl mb-6">SLM1</h4>
            <ul class="space-y-2">
                <li><a href="#" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
                <li><a href="admin/dashboard.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-database"></i> Management DB</a></li>
                <li><a href="Manage Page/index.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-cogs"></i> Admin Page</a></li>
                <li><a href="prescription_form.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-prescription-bottle-alt"></i> Prescription</a></li>
                <li><a href="print_prescription.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-file-medical-alt"></i> & Invoice</a></li>
                <li><a href="Patient/input_patient_info.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-user-plus"></i> Input Patient Info</a></li>
                <li><a href="Doctor_info/doctor_info.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-user-md"></i> Input Doctor Info</a></li>
                <li><a href="Medicine_info/medicine_info.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-pills"></i> Medicine Input</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-dollar-sign"></i> Add Receiver Money</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-capsules"></i> Add Medicine</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-notes-medical"></i> Add Diagnosis</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-hand-holding-usd"></i> Add Receiver Money</a></li>
                <li><a href="report.php" class="flex items-center gap-3 px-4 py-2 hover:bg-teal-500 rounded transition"><i class="fas fa-chart-line"></i> Reports</a></li>
            </ul>
        </div>
        <div class="border-t border-gray-500 pt-4">
            <a href="logout.php" class="flex items-center gap-3 px-4 py-2 hover:bg-red-600 rounded transition"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <h2 class="text-2xl font-bold text-gray-800 border-b border-gray-300 pb-3 mb-6">Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition text-center">
                <h5 class="text-blue-600 font-semibold mb-2"><i class="fas fa-notes-medical"></i> Prescription</h5>
                <p class="text-2xl font-bold text-blue-600">45</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition text-center">
                <h5 class="text-green-600 font-semibold mb-2"><i class="fas fa-file-invoice-dollar"></i> Invoice</h5>
                <p class="text-2xl font-bold text-green-600">120</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition text-center">
                <h5 class="text-yellow-500 font-semibold mb-2"><i class="fas fa-user-edit"></i> NSF Entry</h5>
                <p class="text-2xl font-bold text-yellow-500">30</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition text-center">
                <h5 class="text-red-500 font-semibold mb-2"><i class="fas fa-camera"></i> Scan Doc</h5>
                <p class="text-2xl font-bold text-red-500">30</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition text-center col-span-full sm:col-span-2 lg:col-span-1">
                <h5 class="text-cyan-600 font-semibold mb-2"><i class="fas fa-chart-bar"></i> Reports</h5>
                <p class="text-2xl font-bold text-cyan-600">15</p>
            </div>
        </div>

        <h3 class="text-xl font-semibold mb-4">Recent Reports</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
                <thead class="bg-gray-800 text-white text-left">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Report Type</th>
                    <th class="px-4 py-2">Generated By</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
                </thead>
                <tbody class="text-gray-700">
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">2025-05-01</td>
                    <td class="px-4 py-2">Prescription Report</td>
                    <td class="px-4 py-2">Admin</td>
                    <td class="px-4 py-2">✅ Completed</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">2025-05-02</td>
                    <td class="px-4 py-2">Invoice Report</td>
                    <td class="px-4 py-2">Admin</td>
                    <td class="px-4 py-2">⏳ Pending</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">2025-05-03</td>
                    <td class="px-4 py-2">NSF Data Entry</td>
                    <td class="px-4 py-2">Admin</td>
                    <td class="px-4 py-2">✅ Completed</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">2025-05-04</td>
                    <td class="px-4 py-2">Scan Document</td>
                    <td class="px-4 py-2">Admin</td>
                    <td class="px-4 py-2">✅ Completed</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">2025-05-05</td>
                    <td class="px-4 py-2">Reports Summary</td>
                    <td class="px-4 py-2">Admin</td>
                    <td class="px-4 py-2">⏳ Pending</td>
                </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>
