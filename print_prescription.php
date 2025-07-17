<?php
// Connect to the database
include 'db_connect.php';
// Save data if POST
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
            $unitPrice = 0;
            if (!empty($med['name'])) {
                $priceStmt = $pdo->prepare("SELECT price FROM medicine_prices WHERE name = ?");
                $priceStmt->execute([$med['name']]);
                $priceData = $priceStmt->fetch();
                $unitPrice = $priceData ? $priceData['price'] : 0;
            }

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

        // Save invoice
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

// Load last prescription
$stmt = $pdo->query("SELECT id FROM prescriptions ORDER BY id DESC LIMIT 1");
$lastPrescription = $stmt->fetch();
$selectedPrescription = null;
$grandTotal = 0;

if ($lastPrescription) {
    $id = $lastPrescription['id'];

    $stmt = $pdo->prepare("SELECT * FROM prescriptions WHERE id = ?");
    $stmt->execute([$id]);
    $prescription = $stmt->fetch();

    $stmt2 = $pdo->prepare("SELECT m.name, m.morning, m.afternoon, m.evening, m.night, m.quantity, m.instructions, m.unit_price FROM medicines m WHERE m.prescription_id = ?");
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
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300&display=swap" rel="stylesheet">
    <style>
        .khmer-font {
            font-family: 'Kh Muol','Noto Sans Khmer', sans-serif;
            font-weight: 300; /* light */
        }
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
                width: 100% !important;
                height: auto !important;
                margin: 0;
                padding: 0;
                border: none;
                box-shadow: none;
            }
            #printButton {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
                break-after: page;
            }
        }

        .page-break {
            page-break-after: always;
            break-after: page;
        }
    </style>
</head>
<body class="bg-gray-100 p-0 m-0 font-sans" style="font-family: 'Khmer OS Battambang', sans-serif;">

<!-- Print Button -->
<div class="flex justify-between items-center p-4 bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">

    <form method="POST" id="serviceForm">
        <div class="flex items-center">
            <input id="prescription-radio" type="radio" value="clean_wound" name="document-type"
                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                   onchange="document.getElementById('serviceForm').submit()"
                <?php if (isset($_POST['document-type']) && $_POST['document-type'] == 'clean_wound') echo 'checked'; ?>>
            <label for="prescription-radio" class="ml-2 text-sm font-medium text-gray-700">
                Clean Wound
            </label>
        </div>
        <div class="flex items-center">
            <input id="invoice-radio" type="radio" value="consultation" name="document-type"
                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                   onchange="document.getElementById('serviceForm').submit()"
                <?php if (!isset($_POST['document-type']) || $_POST['document-type'] == 'consultation') echo 'checked'; ?>>
            <label for="invoice-radio" class="ml-2 text-sm font-medium text-gray-700">
                Consultation
            </label>
        </div>
    </form>


    <button id="printButton" onclick="window.print()"
            class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg shadow-md transition duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
       Print
    </button>
</div>

<!-- Invoice Container -->
<div id="invoice" class="w-full max-w-full h-auto bg-white border border-gray-400 p-6 overflow-visible">

    <!-- Page 1: Prescription -->
    <div class="page-break">
        <div class="flex justify-between items-center mb-6">
            <img src="pic/left.png" alt="Logo Left" class="h-10 w-auto" />
            <div class="text-center">
                <h1 class="khmer-font text-sm font-bold text-gray-700">មន្ទីរពេទ្យពហុព្យាបាល សុខលាភមេត្រី</h1>
                <h1 class="text-sm font-bold text-gray-700">SOK LEAP METREY POLYCLINICH</h1>
                <p class="text-lg font-bold text-gray-500">វេជ្ជបញ្ជា</p>
            </div>
            <img src="pic/right.png" alt="Logo Right" class="h-10 w-auto" />
        </div>

        <?php if ($selectedPrescription): ?>
            <div class="grid grid-cols-2 gap-4 mb-6 text-gray-700 text-sm border-b pb-4">
                <div><span class="font-bold">ឈ្មោះ៖</span> <?php echo htmlspecialchars($selectedPrescription['patientName']); ?></div>
                <div class="flex space-x-6">
                    <div><span class="font-bold">ភេទ៖</span> <?php echo htmlspecialchars($selectedPrescription['gender']); ?></div>
                    <div><span class="font-bold">អាយុ៖</span> <?php echo (int)$selectedPrescription['age']; ?> ឆ្នាំ</div>
                </div>
                <div class="col-span-2"><span class="font-bold">រោគវិនិច្ឆ័យ៖</span> <?php echo htmlspecialchars($selectedPrescription['diagnosis']); ?></div>
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

            <div class="text-sm text-gray-700 text-right mb-4">
                <p>ថ្ងៃខែឆ្នាំ៖ <?php echo date('d F Y'); ?></p>
                <p>គ្រូពេទ្យព្យាបាល</p>
                <div class="border border-white w-40 h-20 ml-auto my-2"></div>


                <p><?php echo htmlspecialchars($selectedPrescription['doctor']); ?></p>
            </div>
        <?php endif; ?>

        <div class="fixed bottom-0 text-sm text-gray-600 text-center w-full px-4 mt-12 print:mt-24">
            <p>អាស័យដ្ឋានៈ ផ្ទះលេខ ៤៧ដេ ផ្លូវលេខ៣៦០, សង្កាត់ បឹងកេងកង១, ខណ្ឌ បឹងកេងកង, ភ្នំពេញ</p>
            <p>ទូរស័ព្ទលេខ៖ ៨៥៥-០២៣ ៦៦៦៦ ២៣៧ / ០១១-៣៩ ៨៨៨៨</p>
        </div>
    </div>

    <!-- Page 2: Invoice -->
    <div>
        <div class="flex justify-between items-center mb-6">
            <img src="pic/left.png" alt="Logo Left" class="h-20 w-auto" />
            <div class="text-center">
                <h1 class="text-xl font-bold text-gray-700">មន្ទីរពេទ្យពហុព្យាបាល សុខលាភមេត្រី</h1>
                <h1 class="text-xl font-bold text-gray-700">SOK LEAP METREY POLYCLINICH</h1>
                <p class="text-lg font-bold text-gray-500">វិក្កយបត្រ</p>
            </div>
            <img src="pic/right.png" alt="Logo Right" class="h-20 w-auto" />
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 text-gray-700 text-sm border-b pb-4">
            <div><span class="font-bold">ឈ្មោះ៖</span> <?php echo htmlspecialchars($selectedPrescription['patientName']); ?></div>
            <div class="flex space-x-6">
                <div><span class="font-bold">ភេទ៖</span> <?php echo htmlspecialchars($selectedPrescription['gender']); ?></div>
                <div><span class="font-bold">អាយុ៖</span> <?php echo (int)$selectedPrescription['age']; ?> ឆ្នាំ</div>
            </div>
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
            $serviceType = $_POST['document-type'] ?? 'consultation';
            $servicePrice = ($serviceType === 'clean_wound') ? 60000 : 40000;
            $grandTotal = $servicePrice;

            // Show service charge as first row
            ?>
            <tr>
                <td class="border px-4 py-2 text-center">1</td>
                <td class="border px-4 py-2">
                    <?php echo ($serviceType === 'clean_wound') ? 'Clean Wound' : 'Consultation'; ?>
                </td>
                <td class="border px-4 py-2 text-right"><?php echo number_format($servicePrice); ?> រៀល</td>
                <td class="border px-4 py-2 text-right">1</td>
                <td class="border px-4 py-2 text-right"><?php echo number_format($servicePrice); ?> រៀល</td>
            </tr>

            <?php
            // Now show medicines list from your saved prescription
            if (!empty($selectedPrescription['medicines'])):
                $index = 2; // start after service row
                foreach ($selectedPrescription['medicines'] as $med):
                    $unitPrice = (float)$med['unit_price'];
                    $quantity = (int)$med['quantity'];
                    $total = $unitPrice * $quantity;
                    $grandTotal += $total;
                    ?>
                    <tr>
                        <td class="border px-4 py-2 text-center"><?php echo $index++; ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($med['name']); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo number_format($unitPrice); ?> រៀល</td>
                        <td class="border px-4 py-2 text-right"><?php echo $quantity; ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo number_format($total); ?> រៀល</td>
                    </tr>
                <?php endforeach; endif; ?>
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
<!--        <section>-->
<!--            <p><strong>ថ្ងៃណាត់៖</strong> ..........................................</p>-->
<!--            <p>សូមយកវេជ្ជបញ្ជាមកជាមួយ ពេលមកពិនិត្យលើកក្រោយ។</p>-->
<!--        </section>-->
        <div class="fixed bottom-0 text-sm text-gray-600 text-center w-full px-4 mt-12 print:mt-24">
            <p>អាស័យដ្ឋានៈ ផ្ទះលេខ ៤៧ដេ ផ្លូវលេខ៣៦០, សង្កាត់ បឹងកេងកង១, ខណ្ឌ បឹងកេងកង, ភ្នំពេញ</p>
            <p>ទូរស័ព្ទលេខ៖ ៨៥៥-០២៣ ៦៦៦៦ ២៣៧ / ០១១-៣៩ ៨៨៨៨</p>
        </div>
    </div>
</div>

</body>
<script>
    // Update the invoice when radio buttons change
    document.querySelectorAll('input[name="document-type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const serviceType = this.value;

            // Update the displayed values
            if (serviceType === 'clean_wound') {
                document.getElementById('service-type-display').textContent = 'Clean Wound';
                document.getElementById('unit-price-display').textContent = '60000';
                document.getElementById('total-price-display').textContent = '60000';
                document.getElementById('grand-total-display').textContent = '60000';
            } else {
                document.getElementById('service-type-display').textContent = 'Consultation';
                document.getElementById('unit-price-display').textContent = '40000';
                document.getElementById('total-price-display').textContent = '40000';
                document.getElementById('grand-total-display').textContent = '40000';
            }
        });
    });
</script>
</html>
