<?php
include __DIR__ . '/../db_connect.php';

$error = '';
$success = '';

// Handle Delete Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    if ($delete_id > 0) {
        try {
            $stmt = $pdo->prepare("DELETE FROM medicine_prices WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success = "Medicine deleted successfully!";
        } catch (PDOException $e) {
            $error = "Failed to delete medicine!";
        }
    }
}

// Handle Insert Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['medicine_name'])) {
    $name = trim($_POST['medicine_name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    if (empty($name) || $price <= 0) {
        $error = 'Please enter valid medicine name and price!';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO medicine_prices (name, price) VALUES (?, ?)");
            $stmt->execute([$name, $price]);
            $success = "Medicine price added successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Medicine already exists!";
            } else {
                $error = "Database error!";
            }
        }
    }
}

// Search medicines
$search = trim($_GET['search'] ?? '');
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM medicine_prices WHERE name LIKE ? ORDER BY id DESC");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM medicine_prices ORDER BY id DESC");
}
$medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Medicine Price Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-4">Medicine Price Management</h2>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Add Medicine Form -->
    <div class="bg-white rounded shadow p-6 mb-8">
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-2">
                <label class="block mb-1 font-medium">Medicine Name</label>
                <input type="text" name="medicine_name" required
                       class="w-full border px-3 py-2 rounded" />
            </div>

            <div>
                <label class="block mb-1 font-medium">Price (៛)</label>
                <input type="number" name="price" step="0.01" min="0" required
                       class="w-full border px-3 py-2 rounded" />
            </div>

            <div class="col-span-2 flex justify-end space-x-4">
                <button type="reset"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:ring focus:ring-gray-400">
                    Clear
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
                    Save Medicine
                </button>
            </div>
        </form>
    </div>

    <!-- Search Form -->
    <form method="GET" id="searchForm" class="mb-4">
        <input
                type="text"
                name="search"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                placeholder="Search medicine..."
                class="border px-3 py-2 rounded w-64"
                autocomplete="off"
                id="searchInput"
        />
    </form>


    <!-- Medicines Table -->
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 w-[50px]">#</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 min-w-[200px]">
                    Medicine Name (<?= count($medicines) ?>)
                </th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 w-[100px]">Price (៛)</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-500 w-[100px]">Action</th>
            </tr>
            </thead>
        </table>

        <!-- Scrollable tbody wrapper -->
        <div class="overflow-y-auto max-h-96">
            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($medicines): ?>
                    <?php foreach ($medicines as $index => $medicine): ?>
                        <tr>
                            <td class="px-4 py-2 w-[50px]"><?= count($medicines) - $index ?></td>
                            <td class="px-4 py-2 min-w-[200px]"><?= htmlspecialchars($medicine['name']) ?></td>
                            <td class="px-4 py-2 w-[100px]"><?= htmlspecialchars($medicine['price']) ?>៛</td>
                            <td class="px-4 py-2 w-[100px]">
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this medicine?');">
                                    <input type="hidden" name="delete_id" value="<?= $medicine['id'] ?>" />
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                            No medicine prices found.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
<script>
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');

    let debounceTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            searchForm.submit();
        }, 500); // Wait 500ms after typing stops
    });
</script>

</html>
