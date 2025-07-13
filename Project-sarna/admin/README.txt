==========================
Clinic Admin Panel Setup
==========================

This project provides a full-featured admin dashboard to manage:
- Prescriptions
- Medicines
- Invoices

Each section allows viewing, editing, and deleting records from the database.

--------------------------
📁 FILE STRUCTURE
--------------------------
Place all files in your web server root directory (e.g., htdocs or www):

1. db.php                  - Database connection
2. dashboard.php           - Main admin panel (view all records with tabs)
3. delete_prescription.php - Delete a prescription
4. edit_prescription.php   - Edit a prescription
5. delete_medicine.php     - Delete a medicine
6. edit_medicine.php       - Edit a medicine
7. delete_invoice.php      - Delete an invoice
8. edit_invoice.php        - Edit an invoice

--------------------------
🛠 DATABASE SETUP
--------------------------

1. Import the database:

   Open phpMyAdmin or your MySQL client and run the following SQL:

   ```sql
   CREATE DATABASE clinic_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   USE clinic_db;

   CREATE TABLE IF NOT EXISTS prescriptions (
     id INT AUTO_INCREMENT PRIMARY KEY,
     patient_name VARCHAR(255),
     age INT,
     gender VARCHAR(10),
     diagnosis TEXT,
     doctor_name VARCHAR(255),
     date DATE
   );

   CREATE TABLE IF NOT EXISTS medicines (
     id INT AUTO_INCREMENT PRIMARY KEY,
     prescription_id INT,
     name VARCHAR(255),
     morning VARCHAR(10),
     afternoon VARCHAR(10),
     evening VARCHAR(10),
     night VARCHAR(10),
     quantity INT,
     instructions TEXT,
     FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
   );

   CREATE TABLE IF NOT EXISTS invoices (
     id INT AUTO_INCREMENT PRIMARY KEY,
     prescription_id INT,
     receive_by VARCHAR(255),
     total_amount INT,
     FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
   );
