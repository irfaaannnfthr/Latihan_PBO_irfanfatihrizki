<?php

class Koneksi {
    private $host = "localhost";
    private $username = "root"; 
    private $password = ""; // Kosongkan jika pakai XAMPP/Laragon bawaan, isi jika ada password
    private $database = "db_latihan_pbo_trpl1a_irfan_fatih_rizki";
    protected $db;

    public function __construct() {
        try {
            // Menghubungkan ke MySQL menggunakan PDO
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
            $this->db = new PDO($dsn, $this->username, $this->password);
            
            // Set error mode ke Exception agar kalau salah langsung ketahuan
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Jika koneksi gagal
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    // Method untuk mengambil objek koneksi database
    public function getKoneksi() {
        return $this->db;
    }
}

?>