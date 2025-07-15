<?php
include './db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    // ====== UPDATE LOGIC ======
    $id = (int)$_POST['id'];

    $full_name  = $_POST['full_name'];
    $gender     = $_POST['gender'];
    $dob        = $_POST['dob'];
    $department = $_POST['department'];
    $salary     = $_POST['salary'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $address    = $_POST['address'];

    // Get current profile picture
    $stmt = $pdo->prepare("SELECT profile_pic FROM staff WHERE id = ?");
    $stmt->execute([$id]);
    $staff = $stmt->fetch();
    $profile_pic = $staff['profile_pic'];

    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $filename = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            if (!empty($profile_pic) && file_exists($profile_pic)) {
                unlink($profile_pic);
            }
            $profile_pic = $target_file;
        }
    }

    $sql = "UPDATE staff SET 
            full_name = :full_name, gender = :gender, dob = :dob, department = :department, salary = :salary, 
            email = :email, phone = :phone, address = :address, profile_pic = :profile_pic 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name' => $full_name,
        ':gender' => $gender,
        ':dob' => $dob,
        ':department' => $department,
        ':salary' => $salary,
        ':email' => $email,
        ':phone' => $phone,
        ':address' => $address,
        ':profile_pic' => $profile_pic,
        ':id' => $id
    ]);

    echo "<script>alert('Staff updated successfully!'); window.location.href='input_staff_form.php';</script>";
    exit;
}

// ====== SHOW FORM ======
if (!isset($_GET['id'])) {
    header('Location: input_staff_form.php');
    exit;
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM staff WHERE id = ?");
$stmt->execute([$id]);
$staff = $stmt->fetch();

if (!$staff) {
    echo "Staff not found!";
    exit;
}
?>

<!-- HTML Form -->
<h2>Edit Staff</h2>
<form action="update_staff.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $staff['id'] ?>">

    Full Name: <input type="text" name="full_name" value="<?= htmlspecialchars($staff['full_name']) ?>"><br><br>
    Gender:
    <select name="gender">
        <option <?= $staff['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
        <option <?= $staff['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
        <option <?= $staff['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
    </select><br><br>

    DOB: <input type="date" name="dob" value="<?= $staff['dob'] ?>"><br><br>
    Department: <input type="text" name="department" value="<?= htmlspecialchars($staff['department']) ?>"><br><br>
    Salary: <input type="number" name="salary" value="<?= $staff['salary'] ?>"><br><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>"><br><br>
    Phone: <input type="text" name="phone" value="<?= htmlspecialchars($staff['phone']) ?>"><br><br>
    Address: <textarea name="address"><?= htmlspecialchars($staff['address']) ?></textarea><br><br>

    Current Picture:
    <?php if (!empty($staff['profile_pic'])): ?>
        <img src="<?= $staff['profile_pic'] ?>" width="100"><br>
    <?php endif; ?>
    Change Picture: <input type="file" name="profile_pic"><br><br>

    <button type="submit">Update Staff</button>
</form>
