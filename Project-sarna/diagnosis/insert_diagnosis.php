<?php
include '../db_connect.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Invalid CSRF token'
        ];
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['search'])) {
        // Search functionality
        $search_term = '%' . $_POST['search_term'] . '%';
        $stmt = $pdo->prepare("SELECT * FROM diagnoses WHERE name LIKE :search ORDER BY name");
        $stmt->execute([':search' => $search_term]);
        $diagnoses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    elseif (isset($_POST['update'])) {
        // Update existing diagnosis
        $id = (int)$_POST['id'];
        $name = trim($_POST["name"] ?? '');

        try {
            $stmt = $pdo->prepare("UPDATE diagnoses SET name = :name WHERE id = :id");
            $stmt->execute([':name' => $name, ':id' => $id]);

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Diagnosis updated successfully!'
            ];
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Error updating diagnosis: '.$e->getMessage()
            ];
        }
    }
    else {
        // Add new diagnosis
        $name = trim($_POST["name"] ?? '');

        try {
            $stmt = $pdo->prepare("INSERT INTO diagnoses (name) VALUES (:name)");
            $stmt->execute([':name' => $name]);

            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Diagnosis added successfully!'
            ];
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } catch (PDOException $e) {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Error adding diagnosis: '.$e->getMessage()
            ];
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    try {
        $stmt = $pdo->prepare("DELETE FROM diagnoses WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Diagnosis deleted successfully!'
        ];
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Error deleting diagnosis: '.$e->getMessage()
        ];
    }
}

// Check if we're editing a diagnosis
$editing = false;
$current_diagnosis = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM diagnoses WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $current_diagnosis = $stmt->fetch(PDO::FETCH_ASSOC);
    $editing = $current_diagnosis !== false;
}

// Default: load all diagnoses
if (!isset($diagnoses)) {
    $stmt = $pdo->query("SELECT * FROM diagnoses ORDER BY name");
    $diagnoses = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Diagnosis Management</title>
    <link rel="icon" type="image/png" href="pic/left.png"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this diagnosis?')) {
                window.location.href = '?delete=' + id;
            }
        }
    </script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <div class="">
        <?php include '../sidebar.php'; ?>
    </div>
    <div class="ml-64 p-8 flex-1">
        <!-- Message Display -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="mb-4 p-4 rounded-md <?= $_SESSION['message']['type'] === 'error' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
                <?= htmlspecialchars($_SESSION['message']['text']) ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-md p-8">
            <h4 class="mb-6 text-xl font-semibold text-blue-600 text-center">
                <?= $editing ? 'Edit Diagnosis' : 'Add New Diagnosis' ?>
            </h4>

            <!-- Add/Edit Diagnosis Form -->
            <form method="POST" class="space-y-6 mb-8">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <?php if ($editing): ?>
                    <input type="hidden" name="id" value="<?= $current_diagnosis['id'] ?>">
                    <input type="hidden" name="update" value="1">
                <?php endif; ?>

                <!-- Diagnosis Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Diagnosis Name</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300"
                           placeholder="Enter diagnosis name"
                           value="<?= htmlspecialchars($editing ? $current_diagnosis['name'] : '') ?>">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
                        <?= $editing ? 'Update Diagnosis' : 'Save Diagnosis' ?>
                    </button>
                    <?php if ($editing): ?>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>"
                           class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:ring focus:ring-gray-400">
                            Cancel
                        </a>
                    <?php else: ?>
                        <button type="reset"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:ring focus:ring-gray-400">
                            Clear
                        </button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Search Form -->
            <div class="mb-6">
                <form method="POST" class="flex gap-2">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="text" name="search_term"
                           class="flex-1 px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300"
                           placeholder="Search diagnoses..."
                           value="<?= htmlspecialchars($_POST['search_term'] ?? '') ?>">
                    <button type="submit" name="search"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:ring focus:ring-green-400">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if (isset($_POST['search'])): ?>
                        <a href="<?= $_SERVER['PHP_SELF'] ?>"
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                            Clear Search
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Diagnosis List -->
            <div class="mt-8">
                <h5 class="text-lg font-semibold text-gray-700 mb-4">
                    <?= isset($_POST['search']) ? 'Search Results' : 'Existing Diagnoses' ?>
                    <span class="text-sm text-gray-500">(<?= count($diagnoses) ?> records)</span>
                </h5>

                <?php if (count($diagnoses) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($diagnoses as $diagnosis): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($diagnosis['id']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($diagnosis['name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="?edit=<?= $diagnosis['id'] ?>"
                                           class="text-blue-600 hover:text-blue-900 mr-3"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" onclick="confirmDelete(<?= $diagnosis['id'] ?>)"
                                           class="text-red-600 hover:text-red-900"
                                           title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination would go here -->
                <?php else: ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-clipboard-list text-4xl mb-2"></i>
                        <p>No diagnoses found</p>
                        <?php if (isset($_POST['search'])): ?>
                            <p class="text-sm mt-2">Try a different search term</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>