<?php
// ============================================================
// TAHAP 3 – Abstraksi
// File: models/Tiket.php
// ============================================================

abstract class Tiket
{
    // Atribut terenkapsulasi (protected)
    protected int    $id_tiket;
    protected string $nama_film;
    protected string $jadwal_tayang;
    protected int    $jumlah_kursi;
    protected float  $hargaDasarTiket;

    public function __construct(
        int    $id_tiket,
        string $nama_film,
        string $jadwal_tayang,
        int    $jumlah_kursi,
        float  $hargaDasarTiket
    ) {
        $this->id_tiket        = $id_tiket;
        $this->nama_film       = $nama_film;
        $this->jadwal_tayang   = $jadwal_tayang;
        $this->jumlah_kursi    = $jumlah_kursi;
        $this->hargaDasarTiket = $hargaDasarTiket;
    }

    // Getter
    public function getIdTiket():         int    { return $this->id_tiket; }
    public function getNamaFilm():        string { return $this->nama_film; }
    public function getJadwalTayang():    string { return $this->jadwal_tayang; }
    public function getJumlahKursi():     int    { return $this->jumlah_kursi; }
    public function getHargaDasarTiket(): float  { return $this->hargaDasarTiket; }

    // Abstract Method (wajib di-override subclass)
    abstract public function hitungTotalHarga(): float;
    abstract public function tampilkanInfoFasilitas(): string;
}