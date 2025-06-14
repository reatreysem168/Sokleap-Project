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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Example data for medicine_prices
INSERT INTO medicine_prices (name, price) VALUES
('Paracetamol', 2000),
('Amoxicillin', 5000),
('Ibuprofen', 3000),
('Vitamin C', 1500);

