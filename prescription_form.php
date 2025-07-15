<?php
require 'db_connect.php';
$medicineList = [];

try {
    $stmt = $pdo->query("SELECT name FROM medicine_prices ORDER BY name ASC");
    $medicineList = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
$diagnosisList = [];
try {
    $stmt = $pdo->query("SELECT name FROM diagnoses ORDER BY name ASC");
    $diagnosisList = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>វេជ្ជបញ្ជា (Prescription Form)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        @font-face {
            font-family: 'Khmer OS Muol Light';
            src: url('fonts/KhmerOSmuollight.ttf') format('truetype');
        }

        .khmer-font {
            font-family: 'Khmer OS Muol Light', 'Khmer OS Muol', 'Khmer', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 p-8 flex-1 p-6 overflow-y-auto bg-gray-50 khmer-font">
        <div class="text-right mb-4">
            <button type="button" onclick="sendToPrint()"
                    class="px-6 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                បោះពុម្ភលទ្ធផល
            </button>
        </div>

        <div class=" bg-white p-7 shadow rounded-lg" id="app">
            <!-- Header -->
            <header class="flex justify-between items-center mb-6">
                <img src="pic/left.png" alt="Left Logo" class="h-14"/>
                <div class="text-center text-2xl font-bold">
                    មន្ទីរពហុព្យាបាល សុខ លាភ មេត្រី<br/>
                    SOK LEAP METREY POLYCLINIC
                </div>
                <img src="pic/right.png" alt="Right Logo" class="h-14"/>
            </header>

            <!-- Patient Form -->
            <form id="patientForm" class="mb-6">
                <h2 class="text-xl font-semibold mb-4">ព័ត៌មានអ្នកជំងឺ</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <input list="patientNames" type="text" id="patientName" name="patientName"
                           placeholder="ឈ្មោះអ្នកជំងឺ" required class="p-2 border rounded"/>
                    <datalist id="diagnosis-list">
                        <?php foreach ($diagnosisList as $diagnosis): ?>
                            <option value="<?= htmlspecialchars($diagnosis) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>

                    <select id="gender" name="gender" required class="p-2 border rounded">
                        <option value="ប្រុស">ប្រុស</option>
                        <option value="ស្រី">ស្រី</option>
                    </select>

                    <input type="number" id="age" name="age" min="0" placeholder="អាយុ" required
                           class="p-2 border rounded w-full"/>
                </div>

                <div class="mt-5">
                    <label for="diagnosis" class="font-semibold">រោគវិនិច្ឆ័យ៖</label>
                    <input type="search" id="diagnosis" name="diagnosis" placeholder="ស្វែងរករោគវិនិច្ឆ័យ..."
                           list="diagnosis-list" class="p-2 border rounded w-full max-w-md"/>
                    <datalist id="diagnosis-list"></datalist>
                </div>

                <button type="submit"
                        class="mt-4 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">រក្សាទុក
                </button>
            </form>

            <!-- Medicine Form -->
            <form id="medicineForm" class="mb-6 hidden">
                <h2 class="text-xl font-semibold mb-4">ព័ត៌មានថ្នាំពេទ្យ</h2>

                <div class="flex flex-col space-y-4 w-full">
                    <div>
                        <label class="font-medium">ឈ្មោះថ្នាំ:</label>
                        <input list="medicineList" type="text" id="medicineName" name="medicineName"
                               required class="p-2 border rounded w-full"/>
                        <datalist id="medicineList" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-auto">
                            <?php foreach ($medicineList as $medicine): ?>
                                <option
                                        value="<?= htmlspecialchars($medicine) ?>"
                                        class="px-4 py-2 hover:bg-blue-50 cursor-pointer transition-colors duration-150"
                                ></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" id="morning" placeholder="ព្រឹក" class="p-2 border rounded w-full"/>
                        </div>
                        <div>
                            <input type="text" id="afternoon" placeholder="រសៀល" class="p-2 border rounded w-full"/>
                        </div>
                        <div>
                            <input type="text" id="evening" placeholder="ល្ងាច" class="p-2 border rounded w-full"/>
                        </div>
                        <div>
                            <input type="text" id="night" placeholder="យប់" class="p-2 border rounded w-full"/>
                        </div>
                        <div>
                            <input type="number" id="quantity" placeholder="ចំនួន" min="1" required
                                   class="p-2 border rounded w-full"/>
                        </div>
                        <div>
                            <input type="text" id="instructions" placeholder="សេចក្ដីណែនាំ"
                                   class="p-2 border rounded w-full"/>
                        </div>
                        <div class="md:col-span-3 flex justify-start">
                            <button type="submit"
                                    class="mt-2 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 w-fit">
                                បន្ថែមថ្នាំ
                            </button>
                        </div>
                    </div>
                </div>
            </form>


            <!-- Prescription Table -->
            <div>
                <h1 class="text-2xl mb-6 text-center">វេជ្ជបញ្ជា</h1>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border border-gray-300 text-center">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1">ល.រ</th>
                            <th class="border px-2 py-1">ឈ្មោះថ្នាំ</th>
                            <th class="border px-2 py-1">ព្រឹក</th>
                            <th class="border px-2 py-1">រសៀល</th>
                            <th class="border px-2 py-1">ល្ងាច</th>
                            <th class="border px-2 py-1">យប់</th>
                            <th class="border px-2 py-1">ចំនួន</th>
                            <th class="border px-2 py-1">របៀបប្រើ</th>
                            <th class="border px-2 py-1">សកម្មភាព</th>
                        </tr>
                        </thead>
                        <tbody id="prescriptionTableBody"></tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Form Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                <section>
                    <p><strong>ថ្ងៃណាត់៖</strong> ..........................................</p>
                    <p>សូមយកវេជ្ជបញ្ជាមកជាមួយ ពេលមកពិនិត្យលើកក្រោយ។</p>
                </section>

                <div class="text-right">
                    <input type="date" id="date" class="p-2 border rounded" required/>
                    <p class="mt-2">គ្រូពេទ្យព្យាបាល</p>
                    <select id="doctorName" class="p-2 border rounded w-full" required>
                        <option value="">ជ្រើសរើស Dr</option>
                        <option value="Dr. SEAN SOKVISAL">Dr. SEAN SOKVISAL</option>
                        <option value="Dr. CHHUN PHEAKDEY">Dr. CHHUN PHEAKDEY</option>
                        <option value="Dr. SOTH SEREYPISETH">Dr. SOTH SEREYPISETH</option>
                    </select>

                    <p class="mt-4">អ្នកទទួលប្រាក់</p>
                    <select id="recieve" class="p-2 border rounded w-full" required>
                        <option value="">ជ្រើសរើសអ្នកទទួលប្រាក់</option>
                        <option value="Sem Reatrey">Sem Reatrey</option>
                        <option value="Seng Chhunyeang">Seng Chhunyeang</option>
                    </select>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer mt-10 text-center text-sm text-gray-600">
                <p>អាសយដ្ឋាន: ផ្ទះលេខ ៤៧ដេ ផ្លូវលេខ ៣៦០, សង្កាត់ បឹងកេងកង១, ខណ្ឌ ចំការមន, ភ្នំពេញ</p>
                <p>ទូរសព្ទ: ៨៥៥-០២៣ ៦៦៦៦ ២៣៧ / ០១១ ៣៩ ៨៨៨៨</p>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    let patientData = {};
    let prescriptions = [];
    let editIndex = null;

    const patientForm = document.getElementById('patientForm');
    const medicineForm = document.getElementById('medicineForm');
    const prescriptionTableBody = document.getElementById('prescriptionTableBody');
    const submitMedicineBtn = document.getElementById('submitMedicineBtn');

    fetch('get_autocomplete_data.php')
        .then(res => res.json())
        .then(data => {
            const medicineList = document.getElementById('medicineList');
            const diagnosisList = document.getElementById('diagnosis-list');
            const patientNames = document.getElementById('patientNames');
            data.medicines.forEach(med => medicineList.innerHTML += `<option value="${med}">`);
            data.diagnoses.forEach(diag => diagnosisList.innerHTML += `<option value="${diag}">`);
            data.patients.forEach(p => patientNames.innerHTML += `<option value="${p}">`);
        });

    patientForm.addEventListener('submit', function (e) {
        e.preventDefault();
        patientData = {
            name: document.getElementById('patientName').value.trim(),
            age: document.getElementById('age').value.trim(),
            gender: document.getElementById('gender').value.trim(),
            diagnosis: document.getElementById('diagnosis').value.trim(),
        };
        if (!patientData.name || !patientData.age || !patientData.gender) {
            alert("សូមបំពេញព័ត៌មានអ្នកជំងឺគ្រប់ទិន្នន័យ");
            return;
        }
        medicineForm.classList.remove('hidden');
        patientForm.querySelectorAll('input, select, button').forEach(el => el.disabled = true);
    });

    medicineForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const med = {
            name: document.getElementById('medicineName').value.trim(),
            morning: document.getElementById('morning').value.trim(),
            afternoon: document.getElementById('afternoon').value.trim(),
            evening: document.getElementById('evening').value.trim(),
            night: document.getElementById('night').value.trim(),
            quantity: document.getElementById('quantity').value.trim(),
            instructions: document.getElementById('instructions').value.trim(),
        };
        if (!med.name || !med.quantity || isNaN(med.quantity) || Number(med.quantity) <= 0) {
            alert("សូមបញ្ចូលឈ្មោះថ្នាំ និងចំនួនត្រឹមត្រូវ");
            return;
        }

        if (editIndex !== null) {
            prescriptions[editIndex] = med;
            editIndex = null;
            submitMedicineBtn.textContent = 'បន្ថែមថ្នាំ';
        } else {
            prescriptions.push(med);
        }

        renderTable();
        medicineForm.reset();
    });

    function renderTable() {
        prescriptionTableBody.innerHTML = '';
        prescriptions.forEach((med, index) => {
            prescriptionTableBody.innerHTML += `
                <tr>
                    <td class="border px-2 py-1">${index + 1}</td>
                    <td class="border px-2 py-1">${med.name}</td>
                    <td class="border px-2 py-1">${med.morning}</td>
                    <td class="border px-2 py-1">${med.afternoon}</td>
                    <td class="border px-2 py-1">${med.evening}</td>
                    <td class="border px-2 py-1">${med.night}</td>
                    <td class="border px-2 py-1">${med.quantity}</td>
                    <td class="border px-2 py-1">${med.instructions}</td>
                    <td class="border px-2 py-1">
                        <button onclick="editPrescription(${index})" class="text-blue-500 hover:underline mr-2">កែប្រែ</button>
                        <button onclick="deletePrescription(${index})" class="text-red-500 hover:underline">លុប</button>
                    </td>
                </tr>`;
        });
    }

    function editPrescription(index) {
        const med = prescriptions[index];
        document.getElementById('medicineName').value = med.name;
        document.getElementById('morning').value = med.morning;
        document.getElementById('afternoon').value = med.afternoon;
        document.getElementById('evening').value = med.evening;
        document.getElementById('night').value = med.night;
        document.getElementById('quantity').value = med.quantity;
        document.getElementById('instructions').value = med.instructions;
        editIndex = index;
        submitMedicineBtn.textContent = 'បន្ទាន់សម័យថ្នាំ';
    }

    function deletePrescription(index) {
        prescriptions.splice(index, 1);
        renderTable();
        if (editIndex === index) {
            medicineForm.reset();
            editIndex = null;
            submitMedicineBtn.textContent = 'បន្ថែមថ្នាំ';
        }
    }

    function sendToPrint() {
        if (prescriptions.length === 0) return alert("សូមបញ្ចូលថ្នាំមុនបោះពុម្ភ");

        const dateVal = document.getElementById('date').value;
        const doctorVal = document.getElementById('doctorName').value;
        const receiveVal = document.getElementById('recieve').value;

        if (!dateVal || !doctorVal || !receiveVal)
            return alert("សូមបំពេញថ្ងៃ គ្រូពេទ្យ និងអ្នកទទួលប្រាក់");

        const formData = new FormData();
        formData.append('patientName', patientData.name);
        formData.append('age', patientData.age);
        formData.append('gender', patientData.gender);
        formData.append('diagnosis', patientData.diagnosis);
        formData.append('doctorName', doctorVal);
        formData.append('recieve', receiveVal);
        formData.append('date', dateVal);
        formData.append('medicines', JSON.stringify(prescriptions));

        fetch('print_prescription.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.text())
            .then(html => {
                document.open();
                document.write(html);
                document.close();
                window.onload = () => window.print();
            })
            .catch(err => alert("បញ្ហា: " + err.message));
    }
</script>
</body>
</html>
