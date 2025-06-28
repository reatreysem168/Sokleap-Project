<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>វេជ្ជបញ្ជា (Prescription Form)</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
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
<body class="bg-gray-50 khmer-font">
<div class="text-right p-4">
    <button type="button" onclick="sendToPrint()" class="px-6 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">បោះពុម្ភលទ្ធផល</button>
</div>

<div class="max-w-4xl mx-auto bg-white p-7 shadow rounded-lg" id="app">
    <header class="flex justify-between items-center mb-6">
        <img src="pic/left.png" alt="Left Logo" class="h-14" />
        <div class="text-center text-2xl font-bold">
            មន្ទីរពហុព្យាបាល​ សុខ លាភ មេត្រី<br/>
            SOK LEAP METREY POLYCLINIC
        </div>
        <img src="pic/right.png" alt="Right Logo" class="h-14" />
    </header>

    <form id="patientForm" class="mb-6">
        <h2 class="text-xl font-semibold mb-4">ព័ត៌មានអ្នកជំងឺ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input list="patientNames" type="text" id="patientName" placeholder="ឈ្មោះអ្នកជំងឺ" required class="p-2 border rounded" />
            <datalist id="patientNames"></datalist>

            <select id="gender" required class="p-2 border rounded">
                <option value="">ជ្រើសរើសភេទ</option>
                <option value="ប្រុស">ប្រុស</option>
                <option value="ស្រី">ស្រី</option>
                <option value="ផ្សេងៗ">ផ្សេងៗ</option>
            </select>

            <div>
                <label for="age" class="block">អាយុ (ឆ្នាំ)</label>
                <input type="number" id="age" min="0" placeholder="អាយុ" required class="p-2 border rounded w-full" />
            </div>
        </div>

        <div class="mt-5">
            <label for="diagnosis" class="font-semibold">រោគវិនិច្ឆ័យ៖</label>
            <input type="search" id="diagnosis" name="diagnosis" placeholder="ស្វែងរករោគវិនិច្ឆ័យ..." list="diagnosis-list" class="p-2 border rounded w-full max-w-md" />
            <datalist id="diagnosis-list"></datalist>
        </div>

        <button type="submit" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">រក្សាទុក</button>
    </form>

    <form id="medicineForm" class="mb-6 hidden">
        <h2 class="text-xl font-semibold mb-4">ព័ត៌មានថ្នាំពេទ្យ</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex flex-col">
                <label class="font-medium">ឈ្មោះថ្នាំ:</label>
                <input list="medicineList" type="text" id="medicineName" required class="p-2 border rounded w-full" />
                <datalist id="medicineList"></datalist>
            </div>

            <div class="flex flex-col">
                <label class="font-medium">ព្រឹក:</label>
                <input type="text" id="morning" class="p-2 border rounded" />
            </div>

            <div class="flex flex-col">
                <label class="font-medium">រសៀល:</label>
                <input type="text" id="afternoon" class="p-2 border rounded" />
            </div>

            <div class="flex flex-col">
                <label class="font-medium">ល្ងាច:</label>
                <input type="text" id="evening" class="p-2 border rounded" />
            </div>

            <div class="flex flex-col">
                <label class="font-medium">យប់:</label>
                <input type="text" id="night" class="p-2 border rounded" />
            </div>

            <div class="flex flex-col">
                <label class="font-medium">ចំនួន:</label>
                <input type="number" id="quantity" min="1" required class="p-2 border rounded" />
            </div>

            <div class="flex flex-col">
                <label class="font-medium">សេចក្ដីណែនាំ:</label>
                <input type="text" id="instructions" class="p-2 border rounded" />
            </div>
        </div>

        <button type="submit" class="mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700" id="submitMedicineBtn">បន្ថែមថ្នាំ</button>
    </form>

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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
        <section class="footer-e">
            <p><strong>ថ្ងៃណាត់៖</strong> ..........................................</p>
            <p>សូមយកវេជ្ជបញ្ជាមកជាមួយ ពេលមកពិនិត្យលើកក្រោយ។</p>
        </section>

        <div class="text-right">
            <input type="date" id="date" class="p-2 border rounded" required />
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

    <div class="footer mt-10 text-center text-sm text-gray-600">
        <p>អាសយដ្ឋាន: ផ្ទះលេខ ៤៧ដេ ផ្លូវលេខ ៣៦០,​ សង្កាត់ បឹងកេងកង១,​ ខណ្ឌ ចំការមន, ភ្នំពេញ</p>
        <p>ទូរសព្ទ: ៨៥៥-0២៣ ៦៦៦៦ ២៣៧ / 0១១ ៣៩ ៨៨៨៨</p>
    </div>
</div>

<script>
    let patientData = {};
    let prescriptions = [];
    let editIndex = null;

    const patientForm = document.getElementById('patientForm');
    const medicineForm = document.getElementById('medicineForm');
    const prescriptionTableBody = document.getElementById('prescriptionTableBody');
    const submitMedicineBtn = document.getElementById('submitMedicineBtn');

    // Load autocomplete data (patients, medicines, diagnoses)
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

        // Basic validation
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

        // Validate medicine name and quantity
        if (!med.name) {
            alert("សូមបញ្ចូលឈ្មោះថ្នាំ");
            return;
        }
        if (!med.quantity || isNaN(med.quantity) || Number(med.quantity) <= 0) {
            alert("សូមបញ្ចូលចំនួនថ្នាំត្រឹមត្រូវ");
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
            const row = document.createElement('tr');
            row.innerHTML = `
          <td class="border px-2 py-1">${index + 1}</td>
          <td class="border px-2 py-1">${med.name}</td>
          <td class="border px-2 py-1">${med.morning}</td>
          <td class="border px-2 py-1">${med.afternoon}</td>
          <td class="border px-2 py-1">${med.evening}</td>
          <td class="border px-2 py-1">${med.night}</td>
          <td class="border px-2 py-1">${med.quantity}</td>
          <td class="border px-2 py-1">${med.instructions}</td>
          <td class="border px-2 py-1">
            <button type="button" onclick="editPrescription(${index})" class="text-blue-500 hover:underline mr-2">កែប្រែ</button>
            <button type="button" onclick="deletePrescription(${index})" class="text-red-500 hover:underline">លុប</button>
          </td>
        `;
            prescriptionTableBody.appendChild(row);
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
        if (prescriptions.length === 0) {
            alert("សូមបញ្ចូលថ្នាំមួយចំនួន មុនពេលបោះពុម្ភ");
            return;
        }

        const dateVal = document.getElementById('date').value;
        const doctorVal = document.getElementById('doctorName').value;
        const receiveVal = document.getElementById('recieve').value;

        if (!dateVal || !doctorVal || !receiveVal) {
            alert("សូមបំពេញថ្ងៃ ក្រុមគ្រូពេទ្យ និងអ្នកទទួលប្រាក់");
            return;
        }

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
            .then(response => response.text())
            .then(html => {
                document.open();
                document.write(html);
                document.close();
                window.onload = () => window.print();
            })
            .catch(err => alert("Failed to save prescription: " + err.message));
    }
</script>
</body>
</html>
