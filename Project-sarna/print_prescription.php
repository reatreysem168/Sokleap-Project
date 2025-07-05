<?php
// Connect to the database
try {
//     $pdo = new PDO('mysql:host=localhost;dbname=clinic_db;charset=utf8', 'root', '');
$pdo = new PDO('mysql:host=localhost;port=3307;dbname=clinic_db;charset=utf8', 'root', '1234');
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
            // Get unit price from medicine_prices table if available
            $unitPrice = 0;
            if (!empty($med['name'])) {
                $priceStmt = $pdo->prepare("SELECT price FROM medicine_prices WHERE name = ?");
                $priceStmt->execute([$med['name']]);
                $priceData = $priceStmt->fetch();
                $unitPrice = $priceData ? $priceData['price'] : 0;
            }

            // Override with manual price if provided
            if (isset($med['unit_price']) && is_numeric($med['unit_price'])) {
                $unitPrice = (float)$med['unit_price'];
            }

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

        // Also save to invoices table
        $grandTotal = 0;
        foreach ($medicines as $med) {
            $quantity = (int)($med['quantity'] ?? 0);
            $unitPrice = isset($med['unit_price']) && is_numeric($med['unit_price']) ? (float)$med['unit_price'] : 0;
            $grandTotal += $quantity * $unitPrice;
        }

        $stmt = $pdo->prepare("INSERT INTO invoices (prescription_id, receive_by, total_amount) VALUES (?, ?, ?)");
        $stmt->execute([
            $prescriptionId,
            $_POST['doctorName'] ?? 'Unknown',
            $grandTotal
        ]);

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

    // Calculate grand total
    foreach ($medicines as $med) {
        $grandTotal += (float)$med['unit_price'] * (int)$med['quantity'];
    }
}

date_default_timezone_set('Asia/Phnom_Penh');
$formattedDate = date('d F Y');
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8" />
    <title>វិក្កយបត្រ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #invoice,
            #invoice * {
                visibility: visible;
            }
            #invoice {
                position: absolute;
                left: 0;
                top: 0;
                width: 100vw !important;
                height: 100vh !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }
            #printButton {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-0 m-0 font-sans" style="font-family: 'Khmer OS Battambang', sans-serif;">

<!-- Print Button -->
<div class="flex justify-center mb-4 p-4 bg-gray-100 sticky top-0 z-50">
    <button
            id="printButton"
            onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow"
    >
        បោះពុម្ពវិក្កយបត្រ
    </button>
</div>

<!-- Invoice & Prescription Content -->
<div
        id="invoice"
        class="w-screen h-screen bg-white border border-gray-400 p-6 overflow-y-auto relative shadow-md rounded-none max-w-none"
>
    <!-- Prescription Page (NO PRICES) -->
    <div class="page-break">
        <div class="flex justify-between items-center mb-6">
            <img src="pic/left.png" alt="Logo Left" class="h-20 w-auto" />
            <div class="text-center">
                <h1 class="text-xl font-bold text-gray-700">មន្ទីរពេទ្យពហុព្យាបាល សុខលាភមេត្រី</h1>
                <p class="text-lg font-bold text-gray-500">វេជ្ជបញ្ជា</p>
            </div>
            <img src="pic/right.png" alt="Logo Right" class="h-20 w-auto" />
        </div>

        <?php if ($selectedPrescription): ?>
            <div class="grid grid-cols-2 gap-4 mb-6 text-gray-700 text-sm border-b pb-4">
                <div>
                    <span class="font-bold">ឈ្មោះ៖</span>
                    <?php echo htmlspecialchars($selectedPrescription['patientName']); ?>
                </div>
                <div class="flex space-x-6">
                    <div>
                        <span class="font-bold">ភេទ៖</span>
                        <?php echo htmlspecialchars($selectedPrescription['gender']); ?>
                    </div>
                    <div>
                        <span class="font-bold">អាយុ៖</span>
                        <?php echo (int)$selectedPrescription['age']; ?> ឆ្នាំ
                    </div>
                </div>
                <div class="col-span-2">
                    <span class="font-bold">រោគវិនិច្ឆ័យ៖</span>
                    <?php echo htmlspecialchars($selectedPrescription['diagnosis']); ?>
                </div>
            </div>

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
                        <td class="border px-4 py-2 text-center"><?php echo $index + 1; ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($med['name']); ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $med['morning']; ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $med['afternoon']; ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $med['evening']; ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $med['night']; ?></td>
                        <td class="border px-4 py-2 text-center"><?php echo $med['quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-right mt-4">
                <p><strong>វេជ្ជបណ្ឌិត៖</strong> <?php echo htmlspecialchars($selectedPrescription['doctor']); ?></p>
            </div>
        <?php endif; ?>

        <div class="text-sm text-gray-600 text-center w-full px-4 mt-12 print:mt-24">
            <p>អាស័យដ្ឋានៈ ផ្លូវ ១៨៨ ផ្ទះលេខ ៨៦០, សង្កាត់ បឹងព្រលឹត, ខណ្ឌ៧មករា, ភ្នំពេញ</p>
            <p>លេខទូរស័ព្ទ៖ ០១២-៣៤៥៦៧៨៩ / ០៩៨-៧៦៥៤៣២</p>
        </div>
    </div>

    <!-- Invoice Page (WITH PRICES) -->
    <div>
        <div class="flex justify-between items-center mb-6">
            <img src="pic/left.png" alt="Logo Left" class="h-20 w-auto" />
            <div class="text-center">
                <h1 class="text-xl font-bold text-gray-700">មន្ទីរពេទ្យពហុព្យាបាល សុខលាភមេត្រី</h1>
                <p class="text-lg font-bold text-gray-500">វិក្កយបត្រ</p>
            </div>
            <img src="pic/right.png" alt="Logo Right" class="h-20 w-auto" />
        </div>

        <table class="w-full text-sm text-left text-gray-700 border-collapse mb-6">
            <thead>
            <tr class="bg-gray-200 text-gray-700">
                <th class="border px-4 py-2 text-center">ល.រ</th>
                <th class="border px-4 py-2">ឈ្មោះថ្នាំ</th>
                <th class="border px-4 py-2 text-right">តម្លៃរាយ</th>
                <th class="border px-4 py-2 text-right">ចំនួន</th>
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
                ?>
                <tr>
                    <td class="border px-4 py-2 text-center"><?php echo $index++; ?></td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($med['name']); ?></td>
                    <td class="border px-4 py-2 text-right"><?php echo number_format($unitPrice); ?> រៀល</td>
                    <td class="border px-4 py-2 text-right"><?php echo $quantity; ?></td>
                    <td class="border px-4 py-2 text-right"><?php echo number_format($total); ?> រៀល</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" class="border px-4 py-2 text-right font-bold">សរុបរួម៖</td>
                <td class="border px-4 py-2 text-right font-bold"><?php echo number_format($grandTotal); ?> រៀល</td>
            </tr>
            </tfoot>
        </table>

        <div class="text-sm text-gray-700 text-right mb-4">
            <p>ថ្ងៃខែឆ្នាំ៖ <?php echo date('d F Y'); ?></p>
            <p>អ្នកទទួលប្រាក់</p>
            <img src="pic/yeang.jpg" alt="Signature" class="h-20 w-auto ml-auto my-2" />
            <p>Seng Chhunyeang</p>
        </div>

        <div class="text-sm text-gray-600 text-center w-full px-4 mt-12 print:mt-24">
            <p>អាស័យដ្ឋានៈ ផ្លូវ ១៨៨ ផ្ទះលេខ ៨៦០, សង្កាត់ បឹងព្រលឹត, ខណ្ឌ៧មករា, ភ្នំពេញ</p>
            <p>លេខទូរស័ព្ទ៖ ០១២-៣៤៥៦៧៨៩ / ០៩៨-៧៦៥៤៣២</p>
        </div>
    </div>
</div>

</body>
</html>
