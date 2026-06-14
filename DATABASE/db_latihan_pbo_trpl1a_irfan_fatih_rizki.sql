
-- Hapus tabel lama jika ada (agar tidak konflik)
DROP TABLE IF EXISTS tabel_tiket;

-- ============================================================
-- BUAT TABEL
-- ============================================================
CREATE TABLE tabel_tiket (
    -- Atribut Global (Induk)
    id_tiket            INT AUTO_INCREMENT PRIMARY KEY,
    nama_film           VARCHAR(100)                    NOT NULL,
    jadwal_tayang       DATETIME                        NOT NULL,
    jumlah_kursi        INT                             NOT NULL,
    harga_dasar_tiket   DECIMAL(10,2)                   NOT NULL,
    jenis_studio        ENUM('Regular','IMAX','Velvet') NOT NULL,

    -- Atribut Spesifik (Anak - Nullable)
    tipe_audio          VARCHAR(50)  NULL,
    lokasi_baris        VARCHAR(10)  NULL,
    kacamata_3d_id      VARCHAR(20)  NULL,
    efek_gerak_fitur    TINYINT(1)   NULL,
    bantal_selimut_pack TINYINT(1)   NULL,
    layanan_butler      TINYINT(1)   NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- ISI DATA SAMPEL (7 Regular + 7 IMAX + 6 Velvet = 20 baris)
-- ============================================================
INSERT INTO tabel_tiket
  (nama_film, jadwal_tayang, jumlah_kursi, harga_dasar_tiket, jenis_studio,
   tipe_audio, lokasi_baris, kacamata_3d_id, efek_gerak_fitur, bantal_selimut_pack, layanan_butler)
VALUES
-- REGULAR (7 baris)
('Laskar Pelangi Reborn',       '2025-07-01 10:00:00', 2, 45000, 'Regular', 'Stereo', 'B', NULL, NULL, NULL, NULL),
('Harimau! Harimau!',           '2025-07-01 13:00:00', 3, 45000, 'Regular', 'Stereo', 'C', NULL, NULL, NULL, NULL),
('Cahaya di Ujung Terowongan',  '2025-07-02 10:30:00', 1, 40000, 'Regular', 'Mono',   'A', NULL, NULL, NULL, NULL),
('Bumi Manusia 2',              '2025-07-02 14:00:00', 4, 40000, 'Regular', 'Stereo', 'D', NULL, NULL, NULL, NULL),
('Setan Kredit',                '2025-07-03 11:00:00', 2, 42000, 'Regular', 'Dolby',  'E', NULL, NULL, NULL, NULL),
('KKN di Desa Penari 3',        '2025-07-03 16:30:00', 5, 42000, 'Regular', 'Stereo', 'F', NULL, NULL, NULL, NULL),
('Si Doel The Movie 4',         '2025-07-04 09:00:00', 2, 38000, 'Regular', 'Mono',   'A', NULL, NULL, NULL, NULL),

-- IMAX (7 baris)
('Avengers: Doomsday',          '2025-07-01 11:00:00', 2, 85000, 'IMAX', NULL, NULL, 'GL-001', 1, NULL, NULL),
('Mission Impossible 9',        '2025-07-01 14:00:00', 3, 85000, 'IMAX', NULL, NULL, 'GL-002', 0, NULL, NULL),
('Jurassic World Rebirth',      '2025-07-02 10:00:00', 1, 90000, 'IMAX', NULL, NULL, 'GL-003', 1, NULL, NULL),
('Transformers One',            '2025-07-02 13:30:00', 4, 90000, 'IMAX', NULL, NULL, 'GL-004', 1, NULL, NULL),
('Fast X Part 2',               '2025-07-03 12:00:00', 2, 80000, 'IMAX', NULL, NULL, 'GL-005', 0, NULL, NULL),
('Superman: Legacy',            '2025-07-03 15:00:00', 3, 80000, 'IMAX', NULL, NULL, 'GL-006', 1, NULL, NULL),
('Captain America: Brave',      '2025-07-04 10:00:00', 2, 85000, 'IMAX', NULL, NULL, 'GL-007', 0, NULL, NULL),

-- VELVET (6 baris)
('Oppenheimer 2',               '2025-07-01 19:00:00', 2, 120000, 'Velvet', NULL, NULL, NULL, NULL, 1, 1),
('Interstellar 2',              '2025-07-01 21:00:00', 2, 120000, 'Velvet', NULL, NULL, NULL, NULL, 1, 0),
('Dune: Messiah',               '2025-07-02 19:30:00', 3, 130000, 'Velvet', NULL, NULL, NULL, NULL, 1, 1),
('Blade Runner 2099',           '2025-07-02 21:30:00', 1, 130000, 'Velvet', NULL, NULL, NULL, NULL, 0, 1),
('The Batman Part 2',           '2025-07-03 20:00:00', 4, 115000, 'Velvet', NULL, NULL, NULL, NULL, 1, 1),
('Wonka 2',                     '2025-07-04 18:00:00', 2, 115000, 'Velvet', NULL, NULL, NULL, NULL, 0, 0);
