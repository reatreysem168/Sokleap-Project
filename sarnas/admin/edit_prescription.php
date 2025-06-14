<?php
// edit_prescription.php
include 'db.php';

if (!isset($_GET['id'])) {
    die('No prescription ID specified.');
}

$id = intval($_GET['id']);

// Fetch existing data
$stmt = $conn->prepare("SELECT * FROM prescriptions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Prescription not found.");
}

$prescription = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = $_POST['patient_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $diagnosis = $_POST['diagnosis'];
    $doctor_name = $_POST['doctor_name'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("UPDATE prescriptions SET patient_name=?, age=?, gender=?, diagnosis=?, doctor_name=?, date=? WHERE id=?");
    $stmt->bind_param("sissssi", $patient_name, $age, $gender, $diagnosis, $doctor_name, $date, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating prescription.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Prescription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-xl">
        <h1 class="text-2xl font-bold mb-6 text-center text-blue-700">Edit Prescription</h1>
        <form method="post" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Patient Name</label>
                <input type="text" name="patient_name" value="<?php echo htmlspecialchars($prescription['patient_name']); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Age</label>
                <input type="number" name="age" value="<?php echo $prescription['age']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <select name="gender" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
                    <option value="Male" <?php if ($prescription['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                    <option value="Female" <?php if ($prescription['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Diagnosis</label>
                <textarea name="diagnosis" rows="4" required class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400"><?php echo htmlspecialchars($prescription['diagnosis']); ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Doctor Name</label>
                <input type="text" name="doctor_name" value="<?php echo htmlspecialchars($prescription['doctor_name']); ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" value="<?php echo $prescription['date']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-between items-center">
                <a href="dashboard.php" class="text-blue-600 hover:underline text-sm">← Back to Dashboard</a>
                <input type="submit" value="Update Prescription" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 cursor-pointer">
            </div>
        </form>
    </div>
</body>
</html>
