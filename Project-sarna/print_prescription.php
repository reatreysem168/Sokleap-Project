<?php
// Connect to the database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=clinic_db;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// If this is a POST request with prescription data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['medicines'])) {
    $pdo->beginTransaction();

    try {
        $inputDate = trim($_POST['date']);
        $formattedInputDate = date('Y-m-d', strtotime($inputDate));

        $stmt = $pdo->prepare("INSERT INTO prescriptions (patient_name, age, gender, diagnosis, doctor_name, date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['patientName'],
            $_POST['age'],
            $_POST['gender'],
            $_POST['diagnosis'],
            $_POST['doctorName'],
            $formattedInputDate
        ]);
        $prescriptionId = $pdo->lastInsertId();

        $medicines = json_decode($_POST['medicines'], true);
        $stmt = $pdo->prepare("INSERT INTO medicines (prescription_id, name, morning, afternoon, evening, night, quantity, instructions, unit_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($medicines as $med) {
            $unitPrice = isset($med['unit_price']) && is_numeric($med['unit_price']) ? (float)$med['unit_price'] : 0;

            $stmt->execute([
                $prescriptionId,
                $med['name'] ?? '',
                $med['morning'] ?? '',
                $med['afternoon'] ?? '',
                $med['evening'] ?? '',
                $med['night'] ?? '',
                $med['quantity'] ?? 0,
                $med['instructions'] ?? '',
                $unitPrice
            ]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Failed to save prescription: " . $e->getMessage());
    }
}

$stmt = $pdo->query("SELECT id FROM prescriptions ORDER BY id DESC LIMIT 1");
$lastPrescription = $stmt->fetch();
$selectedPrescription = null;
$grandTotal = 0;

if ($lastPrescription) {
    $id = $lastPrescription['id'];

    $stmt = $pdo->prepare("SELECT * FROM prescriptions WHERE id = ?");
    $stmt->execute([$id]);
    $prescription = $stmt->fetch();

    $sql = "SELECT m.name, m.morning, m.afternoon, m.evening, m.night, m.quantity, m.instructions, m.unit_price FROM medicines m WHERE m.prescription_id = ?";
    $stmt2 = $pdo->prepare($sql);
    $stmt2->execute([$id]);
    $medicines = $stmt2->fetchAll();

    $selectedPrescription = [
        'patientName' => $prescription['patient_name'],
        'gender' => $prescription['gender'],
        'age' => $prescription['age'],
        'diagnosis' => $prescription['diagnosis'],
        'doctor' => $prescription['doctor_name'],
        'date' => $prescription['date'],
        'medicines' => $medicines
    ];
}

date_default_timezone_set('Asia/Phnom_Penh');
$formattedDate = date('d F Y');
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>វិក្កយបត្រ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #invoice, #invoice * {
                visibility: visible;
            }
            #invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 148mm;
                height: 210mm;
            }
            #printButton {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-6" style="font-family: 'Khmer OS Battambang', sans-serif;">

<!-- Print Button -->
<div class="flex justify-center mb-4">
    <button id="printButton" onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
        បោះពុម្ពវិក្កយបត្រ
    </button>
</div>

<!-- Invoice & Prescription Content -->
<div id="invoice" class="max-w-[148mm] min-h-[210mm] bg-white border border-gray-400 p-6 mx-auto relative shadow-md rounded">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <img src="pic/left.png" alt="Logo Left" class="h-20 w-auto" />
        <div class="text-center">
            <h1 class="text-xl font-bold text-gray-700">មន្ទីរពេទ្យពហុព្យាបាល សុខលាភមេត្រី</h1>
            <p class="text-lg font-bold text-gray-500">វេជ្ជបញ្ជា និង វិក្កយបត្រ</p>
        </div>
        <img src="pic/right.png" alt="Logo Right" class="h-20 w-auto" />
    </div>

    <?php if ($selectedPrescription): ?>
        <!-- Patient Info -->
        <div class="grid grid-cols-2 gap-4 mb-6 text-gray-700 text-sm border-b pb-4">
            <div><span class="font-bold">ឈ្មោះ៖</span> <?= htmlspecialchars($selectedPrescription['patientName']) ?></div>
            <div class="flex space-x-6">
                <div><span class="font-bold">ភេទ៖</span> <?= htmlspecialchars($selectedPrescription['gender']) ?></div>
                <div><span class="font-bold">អាយុ៖</span> <?= (int)$selectedPrescription['age'] ?> ឆ្នាំ</div>
            </div>
            <div class="col-span-2">
                <span class="font-bold">រោគវិនិច្ឆ័យ៖</span> <?= htmlspecialchars($selectedPrescription['diagnosis']) ?>
            </div>
        </div>

        <!-- Prescription Table -->
        <h2 class="text-md font-bold text-gray-700 mb-2">វេជ្ជបញ្ជា</h2>
        <table class="w-full text-sm text-left text-gray-700 border-collapse mb-6">
            <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="border px-4 py-2 text-center">ល.រ</th>
                <th class="border px-4 py-2">ឈ្មោះថ្នាំ</th>
                <th class="border px-4 py-2 text-center">ព្រឹក</th>
                <th class="border px-4 py-2 text-center">ថ្ងៃត្រង់</th>
                <th class="border px-4 py-2 text-center">ល្ងាច</th>
                <th class="border px-4 py-2 text-center">យប់</th>
                <th class="border px-4 py-2 text-center">ចំនួន</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($selectedPrescription['medicines'] as $index => $med): ?>
                <tr>
                    <td class="border px-4 py-2 text-center"><?= $index + 1 ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($med['name']) ?></td>
                    <td class="border px-4 py-2 text-center"><?= $med['morning'] ?></td>
                    <td class="border px-4 py-2 text-center"><?= $med['afternoon'] ?></td>
                    <td class="border px-4 py-2 text-center"><?= $med['evening'] ?></td>
                    <td class="border px-4 py-2 text-center"><?= $med['night'] ?></td>
                    <td class="border px-4 py-2 text-center"><?= $med['quantity'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Invoice Table -->
        <h2 class="text-md font-bold text-gray-700 mb-2">វិក្កយបត្រ</h2>
        <table class="w-full text-sm text-left text-gray-700 border-collapse mb-6">
            <thead>
            <tr class="bg-gray-200 text-gray-700">
                <th class="border px-4 py-2 text-center">ល.រ</th>
                <th class="border px-4 py-2">ឈ្មោះថ្នាំ</th>
                <th class="border px-4 py-2 text-right">តម្លៃរាយ</th>
                <th class="border px-4 py-2 text-right">តម្លៃសរុប</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $index = 1;
            foreach ($selectedPrescription['medicines'] as $med):
                $unitPrice = (float)$med['unit_price'];
                $quantity = (int)$med['quantity'];
                $total = $unitPrice * $quantity;
                $grandTotal += $total;
                ?>
                <tr>
                    <td class="border px-4 py-2 text-center"><?= $index++ ?></td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($med['name']) ?></td>
                    <td class="border px-4 py-2 text-right"><?= number_format($unitPrice) ?> រៀល</td>
                    <td class="border px-4 py-2 text-right"><?= number_format($total) ?> រៀល</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Grand Total -->
        <div class="flex justify-end mb-6">
            <div class="w-1/2 bg-gray-200 p-2 font-bold text-right text-gray-700">
                សរុប: <?= number_format($grandTotal) ?> រៀល
            </div>
        </div>

        <!-- Footer -->
        <div class="text-sm text-gray-700 text-right mb-4">
            <p>ថ្ងៃខែឆ្នាំ៖ <?= date('d F Y') ?></p>
            <p>អ្នកទទួលប្រាក់</p>
            <img src="pic/yeang.jpg" alt="Signature" class="h-20 w-auto ml-auto my-2" />
            <p>Seng Chhunyeang</p>
        </div>

    <?php else: ?>
        <div class="text-center text-red-500 font-semibold text-lg">មិនមានទិន្នន័យលម្អិតសម្រាប់ ID នេះទេ។</div>
    <?php endif; ?>

    <!-- Address Footer -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-sm text-gray-600 text-center w-full px-4">
        <p>អាស័យដ្ឋានៈ ផ្លូវ ១៨៨ ផ្ទះលេខ ៨៦០, សង្កាត់ បឹងព្រលឹត, ខណ្ឌ៧មករា, ភ្នំពេញ</p>
        <p>លេខទូរស័ព្ទ៖ ០១២-៣៤៥៦៧៨៩ / ០៩៨-៧៦៥៤៣២</p>
    </div>
</div>

</body>
</html>
