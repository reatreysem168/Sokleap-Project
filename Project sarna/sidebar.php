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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SLM1 Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <!-- sidebar.php -->
    <aside class="w-64 bg-gray-800 text-white flex flex-col justify-between p-4">
        <div>
            <h2 class="text-center text-xl font-bold mb-4">SLM1</h2>
            <nav class="space-y-2">
                <a href="admin/dashboard.php" class="block px-4 py-2 rounded hover:bg-gray-700 <?= ($current_page == 'dashboard.php') ? 'bg-gray-700' : '' ?>">Management DB</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700 <?= ($current_page == 'dashboard.php') ? 'bg-gray-700' : '' ?>">Dashboard</a>
                <a href="prescription_form.php" class="block px-4 py-2 rounded hover:bg-gray-700 <?= ($current_page == 'prescription_form.php') ? 'bg-gray-700' : '' ?>">Prescription</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700 <?= ($current_page == 'patient_history.php') ? 'bg-gray-700' : '' ?>">Patient History</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700 <?= ($current_page == 'nssf_entry.php') ? 'bg-gray-700' : '' ?>">NSSF Entry</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700 <?= ($current_page == 'scan_doc.php') ? 'bg-gray-700' : '' ?>">Scan Doc</a>
                <a href="report.php" class="block px-4 py-2 rounded hover:bg-gray-700 <?= ($current_page == 'report.php') ? 'bg-gray-700' : '' ?>">Reports</a>
            </nav>
        </div>
        <div class="border-t border-gray-600 pt-4">
            <a href="logout.php" class="block px-4 py-2 hover:bg-gray-700 rounded">Logout</a>
        </div>
    </aside>

</div>

<!-- Font Awesome -->
<!--<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>-->
</body>
</html>
