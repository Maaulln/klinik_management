-- Users
CREATE TABLE users (
    id_user SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role VARCHAR(20) NOT NULL CHECK (role IN ('admin', 'doctor', 'cashier', 'patient')),
    id_reference INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sessions
CREATE TABLE sessions (
    id_session SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id_user),
    session_token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cashier staff 
CREATE TABLE petugas_kasir (
    id_kasir SERIAL PRIMARY KEY,
    nama_kasir VARCHAR(250) NOT NULL
);

-- Patients
CREATE TABLE pasien (
    id_pasien SERIAL PRIMARY KEY,
    nama_pasien VARCHAR(250) NOT NULL,
    alamat VARCHAR(1024) NOT NULL
);

-- Registration
CREATE TABLE registrasi (
    id_registrasi SERIAL PRIMARY KEY,
    waktu_registrasi TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_pasien INT REFERENCES pasien(id_pasien)
);

-- Doctors
CREATE TABLE dokter (
    id_dokter SERIAL PRIMARY KEY,
    nama_dokter VARCHAR(200) NOT NULL
);

-- Medical records
CREATE TABLE catatan_medik (
    id_catatan SERIAL PRIMARY KEY,
    id_dokter INT REFERENCES dokter(id_dokter),
    id_pasien INT REFERENCES pasien(id_pasien),
    tanggal_catatan DATE DEFAULT CURRENT_DATE,
    isi_catatan TEXT
);

-- Diseases/conditions
CREATE TABLE penyakit (
    id_penyakit SERIAL PRIMARY KEY,
    nama_penyakit VARCHAR(1024) NOT NULL,
    kategori_penyakit VARCHAR(1024) NOT NULL
);

-- Medications
CREATE TABLE obat (
    id_obat SERIAL PRIMARY KEY,
    nama_obat VARCHAR(1024) NOT NULL,
    harga_obat INT NOT NULL
);

-- Prescriptions
CREATE TABLE resep_obat (
    id_resep SERIAL PRIMARY KEY,
    id_catatan INT REFERENCES catatan_medik(id_catatan),
    tanggal_resep DATE DEFAULT CURRENT_DATE
);

-- Prescription details
CREATE TABLE resep_obat_detail (
    id_resep INT REFERENCES resep_obat(id_resep),
    id_obat INT REFERENCES obat(id_obat),
    jumlah INT NOT NULL DEFAULT 1,
    aturan_pakai VARCHAR(255),
    PRIMARY KEY (id_resep, id_obat)
);

-- Transactions
CREATE TABLE transaksi (
    id_transaksi SERIAL PRIMARY KEY,
    waktu_transaksi TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    harga INT NOT NULL,
    id_kasir INT REFERENCES petugas_kasir(id_kasir),
    id_pasien INT REFERENCES pasien(id_pasien),
    keterangan VARCHAR(1024)
);

-- Initial data - Admin user
INSERT INTO users (username, password, email, role, is_active) 
VALUES ('admin', 'admin123', 'admin@hospital.com', 'admin', TRUE);

-- Initial data - Cashier
INSERT INTO petugas_kasir (nama_kasir) VALUES ('John Doe');
INSERT INTO users (username, password, email, role, id_reference, is_active) 
VALUES ('cashier', 'cashier123', 'cashier@hospital.com', 'cashier', 1, TRUE);

-- Initial data - Doctor
INSERT INTO dokter (nama_dokter) VALUES ('Dr. Jane Smith');
INSERT INTO users (username, password, email, role, id_reference, is_active) 
VALUES ('doctor', 'doctor123', 'doctor@hospital.com', 'doctor', 1, TRUE);

-- Sample medications
INSERT INTO obat (nama_obat, harga_obat) VALUES 
('Paracetamol 500mg', 500),
('Amoxicillin 500mg', 1500),
('Omeprazole 20mg', 1000),
('Loratadine 10mg', 800),
('Ibuprofen 400mg', 600);

-- Sample patient
INSERT INTO pasien (nama_pasien, alamat) VALUES ('Sample Patient', '123 Main St');
INSERT INTO users (username, password, email, role, id_reference, is_active) 
VALUES ('patient', 'pasien123', 'patient@example.com', 'patient', 1, TRUE);

-- Sample medical record
INSERT INTO catatan_medik (id_dokter, id_pasien, isi_catatan) 
VALUES (1, 1, 'Patient came in with flu symptoms. Prescribed rest and medication.');

-- Sample prescription
INSERT INTO resep_obat (id_catatan) VALUES (1);
INSERT INTO resep_obat_detail (id_resep, id_obat, jumlah, aturan_pakai) 
VALUES (1, 1, 10, 'Take 1 tablet every 6 hours as needed for pain or fever.');

-- Sample transaction
INSERT INTO transaksi (harga, id_kasir, id_pasien, keterangan) 
VALUES (5000, 1, 1, 'Payment for consultation and medication');

-- Sample appointment
INSERT INTO registrasi (waktu_registrasi, id_pasien) 
VALUES (CURRENT_TIMESTAMP + INTERVAL '7 days', 1);