<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connect.php';  // Create $pdo

    $full_name  = $_POST['full_name'] ?? '';
    $gender     = $_POST['gender'] ?? '';
    $dob        = $_POST['dob'] ?? null;
    $department = $_POST['department'] ?? '';
    $salary     = $_POST['salary'] ?? 0;
    $email      = $_POST['email'] ?? '';
    $phone      = $_POST['phone'] ?? '';
    $address    = $_POST['address'] ?? '';

    $profile_pic = '';
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $filename = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $profile_pic = $target_file;
        }
    }

    $sql = "INSERT INTO staff (full_name, gender, dob, department, salary, email, phone, address, profile_pic)
            VALUES (:full_name, :gender, :dob, :department, :salary, :email, :phone, :address, :profile_pic)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name'   => $full_name,
        ':gender'      => $gender,
        ':dob'         => $dob,
        ':department'  => $department,
        ':salary'      => $salary,
        ':email'       => $email,
        ':phone'       => $phone,
        ':address'     => $address,
        ':profile_pic' => $profile_pic
    ]);

    echo "<script>alert('Staff added successfully!'); window.location.href='doctor_info.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Input Staff Data</title>
    <link rel="icon" type="image/png" href="pic/left.png"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">
<div class="">
    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full w-64 bg-white shadow">
        <?php include 'sidebar.php'; ?>
    </div>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8">
        <div class="bg-white rounded-xl shadow-md p-8">
            <h4 class="mb-6 text-xl font-semibold text-blue-600 text-center">បញ្ចូលទិន្នន័យបុគ្គលិក</h4>
            <form action="doctor_info.php" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-12 gap-4 mb-4">
                    <div class="col-span-12 md:col-span-6">
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
                    </div>
                    <div class="col-span-6 md:col-span-3">
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select id="gender" name="gender" required
                                class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
                            <option value="">Select</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-span-6 md:col-span-3">
                        <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input type="date" id="dob" name="dob"
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 mb-4">
                    <div class="col-span-12 md:col-span-6">
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input type="text" id="department" name="department" required
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">Salary ($)</label>
                        <input type="number" id="salary" name="salary" min="0" step="0.01" required
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 mb-4">
                    <div class="col-span-12 md:col-span-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Home Address</label>
                    <textarea id="address" name="address" rows="2"
                              class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300"></textarea>
                </div>

                <div class="mb-6">
                    <label for="profile_pic" class="block text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*"
                           class="w-full px-3 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-300">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
                        Save Staff
                    </button>
                    <button type="reset"
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:ring focus:ring-gray-400">
                        Clear
                    </button>
                </div>
            </form>

            <div class="mt-8">
                <?php include "include/staff_list.php"; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
