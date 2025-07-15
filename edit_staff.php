<?php
include 'db_connect.php'; // adjust path if needed

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: doctor_info.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name  = $_POST['full_name'] ?? '';
    $gender     = $_POST['gender'] ?? '';
    $dob        = $_POST['dob'] ?? null;
    $department = $_POST['department'] ?? '';
    $salary     = $_POST['salary'] ?? 0;
    $email      = $_POST['email'] ?? '';
    $phone      = $_POST['phone'] ?? '';
    $address    = $_POST['address'] ?? '';

    $stmt = $pdo->prepare("SELECT profile_pic FROM staff WHERE id = ?");
    $stmt->execute([$id]);
    $staff = $stmt->fetch();
    $profile_pic = $staff['profile_pic'];

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $filename = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            if (!empty($profile_pic) && file_exists($profile_pic)) unlink($profile_pic);
            $profile_pic = $target_file;
        }
    }

    $sql = "UPDATE staff SET full_name=?, gender=?, dob=?, department=?, salary=?, email=?, phone=?, address=?, profile_pic=? WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$full_name, $gender, $dob, $department, $salary, $email, $phone, $address, $profile_pic, $id]);

    header('Location: doctor_info.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM staff WHERE id = ?");
$stmt->execute([$id]);
$staff = $stmt->fetch();
if (!$staff) {
    header('Location: ../doctor_info.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body class="bg-gray-100 font-sans min-h-screen flex">

<!-- Sidebar -->
<div class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg overflow-auto">
    <?php include 'sidebar.php'; ?>
</div>

<!-- Main Content -->
<main class="ml-64 p-8 flex-1 overflow-auto">
    <h2 class="text-3xl font-bold mb-6 text-blue-600 text-center">ធ្វើបចុប្បន្នព័ត៌មានភាពបុគ្គលិទ</h2>

    <form action="edit_staff.php?id=<?= htmlspecialchars($id) ?>" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-md max-w-3xl mx-auto">
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Full Name</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($staff['full_name']) ?>" required
                   class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Gender</label>
            <select name="gender" required
                    class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="Male" <?= $staff['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $staff['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $staff['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Date of Birth</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($staff['dob']) ?>"
                   class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Department</label>
            <input type="text" name="department" value="<?= htmlspecialchars($staff['department']) ?>" required
                   class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Salary</label>
            <input type="number" name="salary" value="<?= htmlspecialchars($staff['salary']) ?>" required
                   class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required
                   class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($staff['phone']) ?>" required
                   class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Address</label>
            <textarea name="address" class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" rows="3"><?= htmlspecialchars($staff['address']) ?></textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-2">Profile Picture</label>
            <?php if (!empty($staff['profile_pic'])): ?>
                <img src="../<?= htmlspecialchars($staff['profile_pic']) ?>" alt="Profile Picture" class="h-24 mb-4 rounded" />
            <?php endif; ?>
            <input type="file" name="profile_pic" class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div class="flex justify-end space-x-4">
            <a href="doctor_info.php" class="px-5 py-2 rounded bg-gray-500 text-white hover:bg-gray-600">Cancel</a>
            <button type="submit" class="px-5 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Update</button>
        </div>
    </form>
</main>
</body>
</html>
