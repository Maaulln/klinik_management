-- Tabel users: Menyimpan data akun user (admin, dokter, kasir, pasien)
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

-- Tabel sessions: Menyimpan data sesi login user
CREATE TABLE sessions (
    id_session SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id_user),
    session_token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel petugas_kasir: Menyimpan data petugas kasir
CREATE TABLE petugas_kasir (
    id_kasir SERIAL PRIMARY KEY,
    nama_kasir VARCHAR(250) NOT NULL
);

-- Tabel pasien: Menyimpan data pasien
CREATE TABLE pasien (
    id_pasien SERIAL PRIMARY KEY,
    nama_pasien VARCHAR(250) NOT NULL,
    alamat VARCHAR(1024) NOT NULL
);

-- Tabel registrasi: Menyimpan data pendaftaran/registrasi pasien (jadwal janji temu)
CREATE TABLE registrasi (
    id_registrasi SERIAL PRIMARY KEY,
    waktu_registrasi TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_pasien INT REFERENCES pasien(id_pasien)
);

-- Tabel dokter: Menyimpan data dokter
CREATE TABLE dokter (
    id_dokter SERIAL PRIMARY KEY,
    nama_dokter VARCHAR(200) NOT NULL
);

-- Tabel catatan_medik: Menyimpan catatan medis pasien
CREATE TABLE catatan_medik (
    id_catatan SERIAL PRIMARY KEY,
    id_dokter INT REFERENCES dokter(id_dokter),
    id_pasien INT REFERENCES pasien(id_pasien),
    tanggal_catatan DATE DEFAULT CURRENT_DATE,
    isi_catatan TEXT
);

-- Tabel penyakit: Menyimpan data penyakit
CREATE TABLE penyakit (
    id_penyakit SERIAL PRIMARY KEY,
    nama_penyakit VARCHAR(1024) NOT NULL,
    kategori_penyakit VARCHAR(1024) NOT NULL
);

-- Tabel obat: Menyimpan data obat
CREATE TABLE obat (
    id_obat SERIAL PRIMARY KEY,
    nama_obat VARCHAR(1024) NOT NULL,
    harga_obat INT NOT NULL
);

-- Tabel resep_obat: Menyimpan data resep obat
CREATE TABLE resep_obat (
    id_resep SERIAL PRIMARY KEY,
    id_catatan INT REFERENCES catatan_medik(id_catatan),
    tanggal_resep DATE DEFAULT CURRENT_DATE
);

-- Tabel resep_obat_detail: Menyimpan detail resep obat (obat, jumlah, aturan pakai)
CREATE TABLE resep_obat_detail (
    id_resep INT REFERENCES resep_obat(id_resep),
    id_obat INT REFERENCES obat(id_obat),
    jumlah INT NOT NULL DEFAULT 1,
    aturan_pakai VARCHAR(255),
    PRIMARY KEY (id_resep, id_obat)
);

-- Tabel transaksi: Menyimpan data pembayaran/transaksi pasien
CREATE TABLE transaksi (
    id_transaksi SERIAL PRIMARY KEY,
    waktu_transaksi TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    harga INT NOT NULL,
    id_kasir INT REFERENCES petugas_kasir(id_kasir),
    id_pasien INT REFERENCES pasien(id_pasien),
    keterangan VARCHAR(1024)
);

-- Data awal user admin
INSERT INTO users (username, password, email, role, is_active) 
VALUES ('admin', 'admin123', 'admin@hospital.com', 'admin', TRUE);

-- Data awal petugas kasir
INSERT INTO petugas_kasir (nama_kasir) VALUES ('John Doe');
INSERT INTO users (username, password, email, role, id_reference, is_active) 
VALUES ('cashier', 'cashier123', 'cashier@hospital.com', 'cashier', 1, TRUE);

-- Data awal dokter
INSERT INTO dokter (nama_dokter) VALUES ('Dr. Jane Smith');
INSERT INTO users (username, password, email, role, id_reference, is_active) 
VALUES ('doctor', 'doctor123', 'doctor@hospital.com', 'doctor', 1, TRUE);

-- Data awal obat
INSERT INTO obat (nama_obat, harga_obat) VALUES 
('Paracetamol 500mg', 500),
('Amoxicillin 500mg', 1500),
('Omeprazole 20mg', 1000),
('Loratadine 10mg', 800),
('Ibuprofen 400mg', 600);

-- Data awal pasien
INSERT INTO pasien (nama_pasien, alamat) VALUES ('Sample Patient', '123 Main St');
INSERT INTO users (username, password, email, role, id_reference, is_active) 
VALUES ('patient', 'pasien123', 'patient@example.com', 'patient', 1, TRUE);

-- Data awal catatan medis pasien
INSERT INTO catatan_medik (id_dokter, id_pasien, isi_catatan) 
VALUES (1, 1, 'Pasien datang dengan gejala flu. Diberikan resep istirahat dan obat.');

-- Data awal resep obat
INSERT INTO resep_obat (id_catatan) VALUES (1);
INSERT INTO resep_obat_detail (id_resep, id_obat, jumlah, aturan_pakai) 
VALUES (1, 1, 10, 'Minum 1 tablet setiap 6 jam jika diperlukan untuk nyeri atau demam.');

-- Data awal transaksi pembayaran pasien
INSERT INTO transaksi (harga, id_kasir, id_pasien, keterangan) 
VALUES (5000, 1, 1, 'Pembayaran konsultasi dan obat');

-- Data awal registrasi/appointment pasien (jadwal janji temu)
INSERT INTO registrasi (waktu_registrasi, id_pasien) 
VALUES (CURRENT_TIMESTAMP + INTERVAL '7 days', 1);