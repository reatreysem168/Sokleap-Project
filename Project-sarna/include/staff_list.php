
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Staff Management</title>
    <link rel="icon" type="image/png" href="pic/left.png"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">
    <div class=" p-8 flex-1 p-8 overflow-auto">
        <!-- Staff Data Table -->
        <div class="bg-white rounded-xl shadow-md p-8">
            <h4 class="mb-6 text-xl font-semibold text-blue-600 text-center">បញ្ជីបុគ្គលិក</h4>

            <?php
            include 'db_connect.php';
            $stmt = $pdo->query("SELECT * FROM staff ORDER BY id DESC");
            $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($staff) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Photo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Salary</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($staff as $member): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (!empty($member['profile_pic'])): ?>
                                        <img src="<?= htmlspecialchars($member['profile_pic']) ?>" alt="Profile" class="h-10 w-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($member['full_name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($member['dob']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?= $member['gender'] === 'Female' ? 'bg-pink-100 text-pink-800' : 'bg-blue-100 text-blue-800' ?>">
                                    <?= htmlspecialchars($member['gender']) ?>
                                </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($member['department']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    $<?= number_format($member['salary'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($member['email']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($member['phone']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <!-- View Button (ID Card) -->
                                    <a href="card_staff/card_staff.php?id=<?= $member['id'] ?>"
                                       class="text-green-600 hover:text-green-900 mr-3"
                                       title="View ID Card">
                                        <i class="fas fa-id-card"></i>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="edit_staff.php?id=<?= $member['id'] ?>"
                                       class="text-blue-600 hover:text-blue-900 mr-3"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete Form -->
                                    <form action="delete_staff.php" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $member['id'] ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure?')"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-users-slash text-4xl mb-2"></i>
                    <p>No staff records found</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>