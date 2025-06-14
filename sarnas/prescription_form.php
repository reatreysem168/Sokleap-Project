
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Prescription Form</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .footer { margin-top: 40px; text-align: center; font-size: 16px; }
    .header { display: flex; align-items: center; justify-content: space-between; }
    .header img { height: 60px; }
    .clinic-name { flex: 1; text-align: center; font-size: 22px; font-weight: bold; line-height: 1.5; }
    .btn-print { margin-right: 10px; margin-top: 20px; padding: 5px; background-color: green; color: white; border: none; cursor: pointer; font-size: 13px; border-radius: 4px; }
    .btn-print:hover { background-color: darkgreen; }
    .footer-right { text-align: right; margin-top: 30px; }
    .footer-right p { margin: 4px 0; }
    .footer-e{ margin-top: 30px; }
      @import url('https://fonts.googleapis.com/css2?family=Khmer&display=swap');
  @font-face {
    font-family: 'Khmer OS Muol Light';
    src: url('fonts/KhmerOSmuollight.ttf') format('truetype'); /* adjust path as needed */
  }

  .khmer-font {
    font-family: 'Khmer OS Muol Light', 'Khmer OS Muol', 'Khmer', sans-serif;
  }
  </style>
</head>
<body class="bg-gray-50">
  <div style="text-align: right;">
    <button type="button" onclick="sendToPrint()" class="mr-5 mt-4 px-6 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">បោះពុម្ភលទ្ធផល</button>
  </div>

  <div class="p-7 max-w-4xl mx-auto bg-white shadow-md rounded-lg" id="app">
    <header class="header">
      <img src="pic/left.png" alt="Left Logo"/>
      <div class="clinic-name">មន្ទីរពហុព្យាបាល​ សុខ លាភ មេត្រី<br/>SOK LEAP METREY POLYCLINIC</div>
      <img src="pic/right.png" alt="Right Logo"/>
    </header>

    <form id="patientForm" class="mb-6">
      <h2 class="text-xl font-semibold mb-4 mt-10">ព័ត៌មានអ្នកជំងឺ</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input list="patientNames" type="text" id="patientName" placeholder="ឈ្មោះអ្នកជំងឺ" required class="p-2 border rounded" />
        <datalist id="patientNames"></datalist>
        <select class="w-40 p-2 border rounded" id="gender" required>
          <option value="">ជ្រើសរើសភេទ</option>
          <option value="ប្រុស">ប្រុស</option>
          <option value="ស្រី">ស្រី</option>
          <option value="ផ្សេងៗ">ផ្សេងៗ</option>
        </select>
        <div>
          <label for="age">អាយុ</label>
          <input class="w-40 p-2 border rounded" type="number" id="age" placeholder="អាយុ" required />
          <label for="year">ឆ្នាំ</label>
        </div>
      </div>
      <p class="text-2xl font-bold mb-5 mt-5">
        <strong>រោគវិនិច្ឆ័យ៖</strong>
        <input type="search" id="diagnosis" name="diagnosis" placeholder="ស្វែងរករោគវិនិច្ឆ័យ..." style="font-size: 16px; padding: 5px; width: 300px;" list="diagnosis-list" />
        <datalist id="diagnosis-list"></datalist>
      </p>
      <button type="submit" id="savePatientBtn" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">រក្សាទុក</button>
    </form>

    <form id="medicineForm" class="mb-6 hidden">
      <h2 class="text-xl font-semibold mb-4">ព័ត៌មានថ្នាំពេទ្យ</h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="flex flex-col">
        <p class="font-medium">ឈ្មោះថ្នាំ:</p>
        <input list="medicineList" type="text" id="medicineName" required class="p-2 border rounded w-full" />
        <datalist id="medicineList"></datalist>
      </div>

      <div class="flex flex-col">
        <p class="font-medium">ព្រឹក:</p>
        <input type="text" id="morning" class="p-2 border rounded" />
      </div>

      <div class="flex flex-col">
        <p class="font-medium">រសៀល:</p>
        <input type="text" id="afternoon" class="p-2 border rounded" />
      </div>

      <div class="flex flex-col">
        <p class="font-medium">ល្ងាច:</p>
        <input type="text" id="evening" class="p-2 border rounded" />
      </div>

      <div class="flex flex-col">
        <p class="font-medium">យប់:</p>
        <input type="text" id="night" class="p-2 border rounded" />
      </div>

      <div class="flex flex-col">
        <p class="font-medium">ចំនួន:</p>
        <input type="text" id="quantity" class="p-2 border rounded" />
      </div>

      <div class="flex flex-col">
        <p class="font-medium">សេចក្ដីណែនាំ:</p>
        <input type="text" id="instructions" class="p-2 border rounded" />
      </div>
    </div>

      <button type="submit" class="mt-4 px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
        <span id="submitLabel">បន្ថែមថ្នាំ</span>
      </button>
    </form>

    <div>
      <h1 class="text-2xl mb-6 text-center khmer-font">វេជ្ជបញ្ជា</h1>
      <div class="overflow-x-auto">
        <table class="w-full table-auto border border-gray-300 text-center">
          <thead class="bg-gray-100">
            <tr>
              <th class="border px-2 py-1">ល.រ</th>
              <th class="border px-2 py-1">ឈ្មោះថ្នាំ</th>
              <th class="border px-2 py-1">ព្រឹក</th>
              <th class="border px-2 py-1">ថ្ងៃ</th>
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <section class="footer-e">
        <p><strong>ថ្ងៃណាត់៖</strong> ..........................................</p>
        <p>សូមយកវេជ្ជបញ្ជាមកជាមួយ ពេលមកពិនិត្យលើកក្រោយ។</p>
      </section>
      <div class="footer-right">
        <input type="date" id="date" class="p-2 border rounded" required />
        <p>គ្រូពេទ្យព្យាបាល</p><br/><br/>
        <select id="doctorName" class="p-2 border rounded" required>
          <option value="">ជ្រើសរើស Dr</option>
          <option value="Dr. SEAN SOKVISAL">Dr. SEAN SOKVISAL</option>
          <option value="Dr. CHHUN PHEAKDEY">Dr. CHHUN PHEAKDEY</option>
          <option value="Dr. SOTH SEREYPISETH">Dr. SOTH SEREYPISETH</option>
        </select><br>
        <select id="recieve" class="p-2 border rounded" required>
          <option value="">ជ្រើសរើសអ្នកទទួលប្រាក់</option>
          <option value="Sem Reatrey">Sem Reatrey</option>
          <option value="Seng Chhunyeang">Seng Chhunyeang</option>
        </select>
      </div>
    </div>

    <div class="footer">
      <p>អាសយដ្ឋាន: ផ្ទះលេខ ៤៧ដេ ផ្លូវលេខ ៣៦០,​ សង្កាត់ បឹងកេងកង១,​ ខណ្ឌ ចំការមន, ភ្នំពេញ</p>
      <p>ទូរសព្ទ: ៨៥៥-0២៣ ៦៦៦៦ ២៣៧ / 0១១ ៣៩ ៨៨៨៨</p>
    </div>
  </div>

  <script>
    // Fetch autocomplete data from PHP
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
  </script>
</body>
</html>


  <script>
    const patientForm = document.getElementById('patientForm');
    const medicineForm = document.getElementById('medicineForm');
    const prescriptionTableBody = document.getElementById('prescriptionTableBody');
    const submitLabel = document.getElementById('submitLabel');

    let patientData = {};
    let prescriptions = [];
    let editIndex = null;

    patientForm.addEventListener('submit', function (e) {
      e.preventDefault();
      patientData = {
        name: document.getElementById('patientName').value,
        age: document.getElementById('age').value,
        gender: document.getElementById('gender').value,
      };
      medicineForm.classList.remove('hidden');
      patientForm.querySelectorAll('input, select, button').forEach(el => el.disabled = true);
    });

    medicineForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const med = {
        name: document.getElementById('medicineName').value,
        morning: document.getElementById('morning').value,
        afternoon: document.getElementById('afternoon').value,
        evening: document.getElementById('evening').value,
        night: document.getElementById('night').value,
        quantity: document.getElementById('quantity').value,
        instructions: document.getElementById('instructions').value,
        doctor: document.getElementById('doctorName').value,
        
        date: document.getElementById('date').value,
      };
      if (editIndex !== null) {
        prescriptions[editIndex] = med;
        editIndex = null;
        submitLabel.textContent = 'Add Medicine';
      } else {
        prescriptions.push(med);
      }
      renderTable();
      medicineForm.reset();
    });
function sendToPrint() {
  const formData = new FormData();
  formData.append('patientName', patientData.name);
  formData.append('age', patientData.age);
  formData.append('gender', patientData.gender);
  formData.append('diagnosis', document.getElementById('diagnosis').value);
  formData.append('doctorName', document.getElementById('doctorName').value);
  formData.append('recieve', document.getElementById('recieve').value);
  formData.append('date', document.getElementById('date').value);
  formData.append('medicines', JSON.stringify(prescriptions));

  fetch('print_prescription.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(html => {
    // Overwrite the current document with the HTML response
    document.open();
    document.write(html);
    document.close();

    // Wait for the DOM to load before printing
    window.onload = () => window.print();
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
      document.getElementById('doctorName').value = med.doctor;
      document.getElementById('date').value = med.date;
      editIndex = index;
      submitLabel.textContent = 'Update Medicine';
    }

    function deletePrescription(index) {
      prescriptions.splice(index, 1);
      renderTable();
      if (editIndex === index) {
        medicineForm.reset();
        editIndex = null;
        submitLabel.textContent = 'Add Medicine';
      }
    }
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
        <button onclick="editPrescription(${index})" class="text-blue-500 hover:underline mr-2">Edit</button>
        <button onclick="deletePrescription(${index})" class="text-red-500 hover:underline">Delete</button>
      </td>
    `;
    prescriptionTableBody.appendChild(row);
  });
}
  </script>