-- Database: clinic_db

CREATE DATABASE IF NOT EXISTS clinic_db;

USE clinic_db;

-- Table: prescriptions
CREATE TABLE IF NOT EXISTS prescriptions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_name VARCHAR(255) NOT NULL,
  age INT NOT NULL,
  gender VARCHAR(20) NOT NULL,
  diagnosis TEXT,
  doctor_name VARCHAR(255),
  date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: medicines
CREATE TABLE IF NOT EXISTS medicines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prescription_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  morning VARCHAR(50) DEFAULT NULL,
  afternoon VARCHAR(50) DEFAULT NULL,
  evening VARCHAR(50) DEFAULT NULL,
  night VARCHAR(50) DEFAULT NULL,
  quantity INT DEFAULT 0,
  instructions TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: invoices
CREATE TABLE IF NOT EXISTS invoices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prescription_id INT NOT NULL,
  receive_by VARCHAR(255) NOT NULL,
  total_amount INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: medicine_prices
CREATE TABLE IF NOT EXISTS medicine_prices (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL UNIQUE,
  price INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Example data for medicine_prices
INSERT INTO medicine_prices (name, price) VALUES
('Paracetamol', 2000),
('Amoxicillin', 5000),
('Ibuprofen', 3000),
('Vitamin C', 1500),
('ABC', 1500);
select * from medicine_prices;
CREATE TABLE patients (
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_name VARCHAR(100) NOT NULL,
  gender VARCHAR(10),
  age INT,
  dob DATE,
  contact VARCHAR(20),
  email VARCHAR(100),
  address TEXT,
  blood_group VARCHAR(10),
  medical_history TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
select * from prescriptions;
ALTER TABLE medicines ADD COLUMN unit_price DECIMAL(10,2) NOT NULL DEFAULT 0;
CREATE TABLE IF NOT EXISTS diagnoses (
                                         id INT AUTO_INCREMENT PRIMARY KEY,
                                         name VARCHAR(255) NOT NULL UNIQUE
);
select * from diagnoses;
# table staff
CREATE TABLE IF NOT EXISTS staff (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     full_name VARCHAR(255) NOT NULL,
                                     gender VARCHAR(10),
                                     dob DATE,
                                     department VARCHAR(100),
                                     salary DECIMAL(10, 2),
                                     email VARCHAR(100),
                                     phone VARCHAR(20),
                                     address TEXT,
                                     profile_pic VARCHAR(255),
                                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
select * FROM staff;
