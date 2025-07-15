<?php
include 'db_connect.php';  // This will create $pdo
// --- Get data from POST safely ---
$full_name  = $_POST['full_name'] ?? '';
$gender     = $_POST['gender'] ?? '';
$dob        = $_POST['dob'] ?? null;
$department = $_POST['department'] ?? '';
$salary     = $_POST['salary'] ?? 0;
$email      = $_POST['email'] ?? '';
$phone      = $_POST['phone'] ?? '';
$address    = $_POST['address'] ?? '';

// --- Handle profile picture upload ---
$profile_pic = ''; // Default empty string if no file uploaded
if (!empty($_FILES['profile_pic']['name'])) {
    $target_dir = "uploads/";

    // Create uploads folder if not exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Generate a unique filename to avoid overwriting
    $filename = time() . "_" . basename($_FILES["profile_pic"]["name"]);
    $target_file = $target_dir . $filename;

    // Move uploaded file from temp location to target folder
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
        $profile_pic = $target_file;
    } else {
        // Optional: handle upload failure (you can redirect or show error)
        die("Failed to upload profile picture.");
    }
}

// --- Prepare and execute SQL insert ---
$sql = "INSERT INTO staff 
        (full_name, gender, dob, department, salary, email, phone, address, profile_pic)
        VALUES
        (:full_name, :gender, :dob, :department, :salary, :email, :phone, :address, :profile_pic)";

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

// --- Redirect back to form with success alert ---
echo "<script>alert('Staff added successfully!'); window.location.href='doctor_info.php';</script>";
exit;

