-- =====================================================
-- SCRIPT SQL LENGKAP UNTUK SIAKAD
-- Sesuai dengan ERD yang diberikan
-- =====================================================

-- =====================================================
-- 1. TABLE DOSEN (Reference Table)
-- =====================================================
CREATE TABLE IF NOT EXISTS dosen (
    nip VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_hp VARCHAR(20),
    alamat TEXT,
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 2. TABLE GOLONGAN
-- =====================================================
CREATE TABLE IF NOT EXISTS golongan (
    id_gol BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_gol VARCHAR(50) NOT NULL,
    program_studi VARCHAR(100),
    angkatan VARCHAR(10),
    dosen_wali VARCHAR(20),
    kapasitas INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (dosen_wali) REFERENCES dosen(nip) ON DELETE SET NULL ON UPDATE CASCADE
);

-- =====================================================
-- 3. TABLE MATAKULIAH
-- =====================================================
CREATE TABLE IF NOT EXISTS mata_kuliah (
    kode_mk VARCHAR(20) PRIMARY KEY,
    nama_mk VARCHAR(100) NOT NULL,
    sks INT NOT NULL,
    semester INT NOT NULL,
    jenis VARCHAR(50),
    deskripsi TEXT,
    silabus TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 4. TABLE RUANG
-- =====================================================
CREATE TABLE IF NOT EXISTS ruang (
    id_ruang VARCHAR(20) PRIMARY KEY,
    nama_ruang VARCHAR(50) NOT NULL,
    gedung VARCHAR(50),
    kapasitas INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- 5. TABLE MAHASISWA
-- =====================================================
CREATE TABLE IF NOT EXISTS mahasiswa (
    nim VARCHAR(20) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    no_hp VARCHAR(20),
    email VARCHAR(100) UNIQUE NOT NULL,
    semester INT NOT NULL,
    id_gol BIGINT UNSIGNED NOT NULL,
    foto VARCHAR(255),
    status VARCHAR(20),
    tahun_masuk VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_gol) REFERENCES golongans(id_gol) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_mahasiswa_gol (id_gol),
    INDEX idx_mahasiswa_semester (semester)
);

-- =====================================================
-- 6. TABLE PENGAMPU (Relasi: Matakuliah + Dosen)
-- =====================================================
CREATE TABLE IF NOT EXISTS pengampu (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(20) NOT NULL,
    nip VARCHAR(20) NOT NULL,
    tahun_akademik VARCHAR(20) NOT NULL,
    semester_akademik VARCHAR(20) NOT NULL,
    status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kode_mk) REFERENCES mata_kuliah(kode_mk) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (nip) REFERENCES dosens(nip) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uk_pengampu (kode_mk, nip, tahun_akademik, semester_akademik),
    INDEX idx_pengampu_mk (kode_mk),
    INDEX idx_pengampu_nip (nip),
    INDEX idx_pengampu_tahun_semester (tahun_akademik, semester_akademik)
);

-- =====================================================
-- 7. TABLE JADWAL_AKADEMIK
-- =====================================================
CREATE TABLE IF NOT EXISTS jadwal_akademik (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hari VARCHAR(20) NOT NULL,
    kode_mk VARCHAR(20) NOT NULL,
    id_ruang VARCHAR(20) NOT NULL,
    id_gol BIGINT UNSIGNED NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    tahun_akademik VARCHAR(20) NOT NULL,
    semester_akademik VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kode_mk) REFERENCES mata_kuliah(kode_mk) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_ruang) REFERENCES ruangs(id_ruang) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_gol) REFERENCES golongans(id_gol) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_jadwal_gol (id_gol),
    INDEX idx_jadwal_ruang (id_ruang),
    INDEX idx_jadwal_mk (kode_mk),
    INDEX idx_jadwal_hari (hari),
    INDEX idx_jadwal_tahun_semester (tahun_akademik, semester_akademik)
);

-- =====================================================
-- 8. TABLE KRS
-- =====================================================
CREATE TABLE IF NOT EXISTS krs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) NOT NULL,
    kode_mk VARCHAR(20) NOT NULL,
    tahun_akademik VARCHAR(20) NOT NULL,
    semester_akademik VARCHAR(20) NOT NULL,
    status_krs VARCHAR(20),
    nilai_akhir VARCHAR(5),
    nilai_angka DECIMAL(5,2),
    status_lulus VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (nim) REFERENCES mahasiswas(nim) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (kode_mk) REFERENCES mata_kuliah(kode_mk) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uk_krs (nim, kode_mk, tahun_akademik, semester_akademik),
    INDEX idx_krs_nim (nim),
    INDEX idx_krs_mk (kode_mk),
    INDEX idx_krs_tahun_semester (tahun_akademik, semester_akademik)
);

-- =====================================================
-- 9. TABLE PRESENSI_AKADEMIK
-- =====================================================
CREATE TABLE IF NOT EXISTS presensi_akademik (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hari VARCHAR(20),
    tanggal DATE NOT NULL,
    nim VARCHAR(20) NOT NULL,
    kode_mk VARCHAR(20) NOT NULL,
    status_kehadiran VARCHAR(20),
    jam_masuk TIME,
    jam_keluar TIME,
    keterangan TEXT,
    pertemuan_ke INT,
    metode_presensi VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (nim) REFERENCES mahasiswas(nim) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (kode_mk) REFERENCES mata_kuliah(kode_mk) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_presensi_nim (nim),
    INDEX idx_presensi_mk (kode_mk),
    INDEX idx_presensi_tanggal (tanggal)
);

-- =====================================================
-- 10. TABLE QR_PRESENSI
-- =====================================================
CREATE TABLE IF NOT EXISTS qr_presensi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(20) NOT NULL,
    nip VARCHAR(20) NOT NULL,
    kode_qr VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    aktif TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (kode_mk) REFERENCES mata_kuliah(kode_mk) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (nip) REFERENCES dosens(nip) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_qr_kode (kode_qr),
    INDEX idx_qr_expires (expires_at)
);

-- =====================================================
-- 11. TABLE USERS (Authentication)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'mahasiswa',
    nim VARCHAR(20),
    nip VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role (role),
    INDEX idx_users_nim (nim),
    INDEX idx_users_nip (nip)
);

-- =====================================================
-- RELASI ANTAR TABEL (Summary)
-- =====================================================
-- Mahasiswa.id_gol → Golongan.id_gol ✓
-- Jadwal_Akademik.kode_mk → Matakuliah.kode_mk ✓
-- Jadwal_Akademik.id_ruang → Ruang.id_ruang ✓
-- Jadwal_Akademik.id_gol → Golongan.id_gol ✓
-- Presensi_Akademik.nim → Mahasiswa.nim ✓
-- Presensi_Akademik.kode_mk → Matakuliah.kode_mk ✓
-- Pengampu.kode_mk → Matakuliah.kode_mk ✓
-- Pengampu.nip → Dosen.nip ✓
-- KRS.nim → Mahasiswa.nim ✓
-- KRS.kode_mk → Matakuliah.kode_mk ✓
