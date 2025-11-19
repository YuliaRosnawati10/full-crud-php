-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Nov 2025 pada 05.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crud-php`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `id_akun` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `akun`
--

INSERT INTO `akun` (`id_akun`, `nama`, `username`, `email`, `password`, `level`) VALUES
(1, 'Muba Teknologi', 'mubatekno', 'mubatekno@gmail.com', '123456', '1'),
(2, 'yulia', 'lia', 'lia@gmail.com', '12345678', '1'),
(5, 'lia', 'yulia', 'yulia@gmail.com', '123456', '1'),
(6, 'yulia', 'yulia', 'yulia@gmail.com', '$2y$10$zoGa1mIOw8pmoidlrVLcw.M1ryb.siFLnxkCQx.F/WwXe2S7QUL7m', '1'),
(7, 'operator barang', 'opmbarang', 'operatorbarang@gmail.com', '$2y$10$UR.eOAitlrexFhdfaHx99ur2OU32B31YpuQMGGfzKjki9hwosQj1m', '2'),
(8, 'operator mahasiswa', 'opmahasiswa', 'operatormahasiswa@gmail.com', '$2y$10$5GukMtj6f0R5f6Xb2GShjeU4zw9UvwSkUwsqZ5PwGf.nY1IvWd0DG', '3');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jumlah` int(50) NOT NULL,
  `harga` int(50) NOT NULL,
  `barcode` varchar(15) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama`, `jumlah`, `harga`, `barcode`, `tanggal`) VALUES
(1, 'Keyboard', 10, 150000, '', '2025-11-12 01:05:06'),
(2, 'Headset', 12, 75000, '', '2025-11-12 01:37:09'),
(7, 'laptop', 10, 100000, '', '2025-11-12 01:37:29'),
(10, 'Kursi', 5, 1500000, '', '2025-11-12 01:46:43'),
(13, 'kaca', 10, 50000, '', '2025-11-14 15:23:03'),
(14, 'meja 2', 10, 50000, '478044', '2025-11-17 06:38:42'),
(15, 'kaca', 10, 500000, '539974', '2025-11-17 07:49:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id_mahasiswa` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `jk` varchar(10) NOT NULL,
  `telepon` varchar(30) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(30) NOT NULL,
  `foto` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mahasiswa`
--

INSERT INTO `mahasiswa` (`id_mahasiswa`, `nama`, `prodi`, `jk`, `telepon`, `alamat`, `email`, `foto`) VALUES
(1, 'Muba Teknologi', 'Teknik Informatika ', 'Laki Laki', '0823767678', '', 'mubatekno@gmail.com', 'foto.jpg'),
(2, 'amelia', 'Teknik Mesin', 'Perempuan', '09876567', '', 'amelian@gmail.com', '691749b90324d.png'),
(13, 'yuliaa', 'Teknik Informatika', 'Perempuan', '098765345', '', 'yulia@gmail.com', '691749918741f.png'),
(14, 'anisa', 'Teknik Informatika', 'Perempuan', '0987654678', '<p><img alt=\"\" src=\"/ckfinder/userfiles/images/foto%20alamat/logo%20login.jpeg\" style=\"height:512px; width:512px\" /></p>\r\n', 'anisa@gmail.com', '691bcc2a29a6e.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`id_akun`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akun`
--
ALTER TABLE `akun`
  MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id_mahasiswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
