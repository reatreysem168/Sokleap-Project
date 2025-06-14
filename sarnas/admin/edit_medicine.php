<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$medicine_id = $_GET['id'];
$message = '';

// Fetch the existing medicine details
$stmt = $conn->prepare("SELECT * FROM medicines WHERE id = ?");
$stmt->bind_param("i", $medicine_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Medicine not found.";
    exit;
}

$medicine = $result->fetch_assoc();
$stmt->close();

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $morning = $_POST['morning'];
    $afternoon = $_POST['afternoon'];
    $evening = $_POST['evening'];
    $night = $_POST['night'];
    $quantity = $_POST['quantity'];
    $instructions = $_POST['instructions'];

    $stmt = $conn->prepare("UPDATE medicines SET name = ?, morning = ?, afternoon = ?, evening = ?, night = ?, quantity = ?, instructions = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $name, $morning, $afternoon, $evening, $night, $quantity, $instructions, $medicine_id);

    if ($stmt->execute()) {
        $message = "Medicine updated successfully.";
        header("Location: dashboard.php?tab=medicines&message=" . urlencode($message));
        exit();
    } else {
        $message = "Error updating medicine.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Medicine</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-2xl">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">Edit Medicine</h2>

        <?php if ($message): ?>
            <p class="text-red-500 text-center mb-4"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Medicine Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($medicine['name']); ?>" required
                       class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dose Timings (Y/N):</label>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm">Morning:</label>
                        <input type="text" name="morning" value="<?php echo $medicine['morning']; ?>"
                               class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm">Afternoon:</label>
                        <input type="text" name="afternoon" value="<?php echo $medicine['afternoon']; ?>"
                               class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm">Evening:</label>
                        <input type="text" name="evening" value="<?php echo $medicine['evening']; ?>"
                               class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm">Night:</label>
                        <input type="text" name="night" value="<?php echo $medicine['night']; ?>"
                               class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Quantity:</label>
                <input type="number" name="quantity" value="<?php echo $medicine['quantity']; ?>"
                       class="mt-1 block w-full p-2 border border-gray-300 rounded-lg">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Instructions:</label>
                <textarea name="instructions" rows="4"
                          class="mt-1 block w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400"><?php echo htmlspecialchars($medicine['instructions']); ?></textarea>
            </div>

            <div class="flex justify-between items-center">
                <a href="dashboard.php?tab=medicines" class="text-green-600 hover:underline text-sm">← Back to Dashboard</a>
                <input type="submit" value="Update Medicine"
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 cursor-pointer">
            </div>
        </form>
    </div>
</body>
</html>
