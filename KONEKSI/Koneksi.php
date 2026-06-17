<?php
// ============================================================
// TAHAP 3 – Konfigurasi Koneksi Database
// File: koneksi/koneksi.php
// ============================================================

define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASSWORD', '');
define('DB_NAME',     'db_latihan_pbo_trpl1a_irfan_fatih_rizki');

/**
 * Mengembalikan koneksi MySQLi ke database.
 * Menggunakan pola Singleton sederhana agar hanya ada satu koneksi aktif.
 */
function getConnection(): mysqli
{
    static $conn = null;

    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if ($conn->connect_error) {
            die('<p style="color:red;font-family:sans-serif;">
                    ❌ Koneksi database gagal: ' . $conn->connect_error . '
                 </p>');
        }

        $conn->set_charset('utf8mb4');
    }

    return $conn;
}