<?php
$reports = [
    ['id' => 1, 'name' => 'John Doe', 'age' => 45, 'diagnosis' => 'Hypertension', 'date' => '2025-05-01'],
    ['id' => 2, 'name' => 'Jane Smith', 'age' => 52, 'diagnosis' => 'Diabetes', 'date' => '2025-05-03'],
    ['id' => 3, 'name' => 'Michael Lee', 'age' => 37, 'diagnosis' => 'Asthma', 'date' => '2025-06-11'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <main class="flex-1 p-6 bg-gray-100">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

        <!-- Cards -->
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

        <!-- Search Form -->
        <h3 class="text-xl font-semibold mb-4">Recent Reports</h3>
        <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label for="searchId" class="block text-sm font-medium text-gray-700 mb-1">Search by ID</label>
                <input type="text" id="searchId" name="searchId" placeholder="Enter ID"
                       class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="<?php echo isset($_GET['searchId']) ? htmlspecialchars($_GET['searchId']) : '' ?>"/>
            </div>
            <div>
                <label for="searchDate" class="block text-sm font-medium text-gray-700 mb-1">Search by Date</label>
                <input type="date" id="searchDate" name="searchDate"
                       class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="<?php echo isset($_GET['searchDate']) ? htmlspecialchars($_GET['searchDate']) : '' ?>"/>
            </div>
            <div>
                <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                    Search
                </button>
            </div>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto bg-white rounded shadow mb-6">
            <table class="min-w-full text-sm text-gray-800 border border-gray-200">
                <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">NO</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Age</th>
                    <th class="px-4 py-2 text-left">Diagnosis</th>
                    <th class="px-4 py-2 text-left">Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                $filtered = array_filter($reports, function ($r) {
                    $matchId = true;
                    $matchDate = true;
                    if (!empty($_GET['searchId'])) {
                        $matchId = (string)$r['id'] === $_GET['searchId'];
                    }
                    if (!empty($_GET['searchDate'])) {
                        $matchDate = $r['date'] === $_GET['searchDate'];
                    }
                    return $matchId && $matchDate;
                });

                foreach ($filtered as $report) {
                    $count++;
                    echo "<tr class='hover:bg-gray-100'>";
                    echo "<td class='px-4 py-2'>" . $count . "</td>";
                    echo "<td class='px-4 py-2'>" . htmlspecialchars($report['name']) . "</td>";
                    echo "<td class='px-4 py-2'>" . htmlspecialchars($report['age']) . "</td>";
                    echo "<td class='px-4 py-2'>" . htmlspecialchars($report['diagnosis']) . "</td>";
                    echo "<td class='px-4 py-2'>" . htmlspecialchars($report['date']) . "</td>";
                    echo "</tr>";
                }

                if ($count === 0) {
                    echo "<tr><td colspan='5' class='px-4 py-2 text-center text-gray-500'>No reports found.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>

        <p class="font-semibold">Total reports: <?php echo $count; ?></p>
    </main>
</div>
</body>
</html>
