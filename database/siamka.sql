-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 09:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siamka`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `id_aset` int(11) NOT NULL,
  `kode_aset` varchar(50) NOT NULL,
  `nama_aset` varchar(100) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `kondisi` enum('baik','rusak','hilang') DEFAULT 'baik',
  `status` enum('tersedia','dipinjam','perbaikan','dihapus') DEFAULT 'tersedia',
  `harga` decimal(12,2) DEFAULT NULL,
  `tanggal_perolehan` date DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `keterangan` text DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id_aset`, `kode_aset`, `nama_aset`, `id_kategori`, `lokasi`, `kondisi`, `status`, `harga`, `tanggal_perolehan`, `foto`, `keterangan`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'AST-2024-0001', 'Laptop Lenovo ThinkPad E14', 1, 'Lab Komputer 1', 'baik', 'tersedia', 8500000.00, '2024-01-10', 'AST_68fe14f1229a02.61046187.jpg', 'Digunakan untuk praktikum mahasiswa.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:32:49'),
(2, 'AST-2024-0002', 'Proyektor Epson EB-X41', 1, 'Ruang Rapat Utama', 'baik', 'tersedia', 5200000.00, '2023-08-14', 'AST_68fe1510c00806.50425738.jpg', 'Sering digunakan untuk presentasi dosen.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:33:20'),
(3, 'AST-2024-0003', 'Printer Canon LBP2900', 1, 'Kantor Tata Usaha', 'rusak', 'tersedia', 2100000.00, '2022-11-25', 'AST_68fe132f77a4d5.39430242.jpg', 'Toner macet, sedang dalam perbaikan.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:25:19'),
(4, 'AST-2024-0004', 'Monitor Dell 24 Inch', 1, 'Lab Komputer 2', 'baik', 'tersedia', 2700000.00, '2023-03-05', 'AST_68fe1309ea7e44.69122201.jpg', 'Monitor tambahan untuk dosen pengawas.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:24:41'),
(5, 'AST-2024-0005', 'Speaker Logitech Z313', 1, 'Ruang Multimedia', 'baik', 'tersedia', 950000.00, '2023-04-10', 'AST_68fe126a0b17b3.21992141.jpg', 'Speaker aktif untuk ruang multimedia.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:22:02'),
(6, 'AST-2024-0006', 'Kursi Kantor Ergonomis', 2, 'Ruang Dosen', 'baik', 'tersedia', 780000.00, '2023-06-05', 'AST_68fe1228a33ca2.36110663.jpg', 'Kursi baru untuk ruang dosen.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:20:56'),
(7, 'AST-2024-0007', 'Meja Kayu Jati', 2, 'Ruang Rapat', 'baik', 'tersedia', 1250000.00, '2022-09-19', 'AST_68fe14398f6261.28166026.jpg', 'Meja rapat utama.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:29:45'),
(8, 'AST-2024-0008', 'Lemari Arsip Besi', 2, 'Kantor TU', 'baik', 'tersedia', 2100000.00, '2023-07-01', 'AST_68fe137c6fd9c1.61321149.jpg', 'Menyimpan dokumen penting kampus.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:26:36'),
(9, 'AST-2024-0009', 'Whiteboard Magnetic', 2, 'Ruang Kelas B2', 'baik', 'tersedia', 600000.00, '2023-01-15', 'AST_68fe1489ac3ed0.16368877.jpg', 'Whiteboard baru untuk kelas B2.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:31:05'),
(10, 'AST-2024-0010', 'Sofa Tunggu 3 Dudukan', 2, 'Lobby Utama', 'rusak', 'tersedia', 2300000.00, '2022-10-22', 'AST_68fe14ca250011.66060693.jpg', 'Diperbaiki karena busa robek.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:32:10'),
(11, 'AST-2024-0011', 'Mobil Operasional McLaren Senna', 3, 'Bawah Masjid', 'baik', 'dipinjam', 160000000.00, '2021-02-15', 'AST_68fb2abe1a0e79.75783483.jpg', 'Mobil dinas rektorat.', NULL, '2025-10-21 10:33:10', '2025-10-26 12:01:52'),
(12, 'AST-2024-0012', 'Motor Listrik Campus Delivery', 3, 'Parkiran Timur', 'hilang', 'tersedia', 21000000.00, '2023-05-09', 'AST_68fb2705effb82.25188589.jpg', 'Digunakan untuk pengiriman dokumen antar fakultas.', NULL, '2025-10-21 10:33:10', '2025-10-24 14:41:05'),
(13, 'AST-2024-0013', 'Mikroskop Olympus CX23', 4, 'Lab Biologi', 'baik', 'tersedia', 8500000.00, '2023-02-12', 'AST_68fb26d3cd34c1.91809635.png', 'Alat pengamatan sel dan jaringan. hehehh', NULL, '2025-10-21 10:33:10', '2025-10-25 16:01:28'),
(14, 'AST-2024-0014', 'Multimeter Digital Sanwa', 4, 'Lab Elektronika', 'baik', 'tersedia', 450000.00, '2024-04-18', 'AST_68fa2a8739c925.74306867.jpg', '', '2025-10-24 14:13:29', '2025-10-21 10:33:10', '2025-10-24 07:13:29'),
(15, 'AST-2024-0015', 'Oven Pengering Sampel', 4, 'Lab Kimia', 'baik', 'tersedia', 3200000.00, '2022-12-30', 'AST_68fa29757c28e5.42973952.png', '', '2025-10-24 14:13:20', '2025-10-21 10:33:10', '2025-10-24 07:13:20'),
(19, 'AST-2025-0018', 'Yamaha r25', 3, 'bawah masjid', 'baik', 'dipinjam', 1000000000.00, '2025-10-14', 'AST_68fa1145374325.43815026.jpg', 'motor sawah', NULL, '2025-10-23 11:28:05', '2025-10-26 12:05:29'),
(24, 'AST-2025-0019', 'mic', 5, 'sumur gayam', 'rusak', 'tersedia', 1231312.00, '2025-10-01', 'default.png', '', '2025-10-24 15:03:06', '2025-10-24 07:41:21', '2025-10-24 08:03:06'),
(25, 'AST-2025-0020', 'mic', 1, 'sumur gayam', 'rusak', 'tersedia', 12313131.00, '2025-10-10', 'default.png', '', '2025-10-24 20:05:20', '2025-10-24 13:05:10', '2025-10-24 13:05:20'),
(26, 'AST-2025-0021', 'kodok', 5, 'fdasdfa', 'hilang', 'dipinjam', 24.00, '2025-10-01', 'AST_68fcf5553d9752.32086102.jpg', 'dsfdsf', '2025-10-25 23:06:20', '2025-10-25 16:05:41', '2025-10-26 10:56:20'),
(27, 'AST-2025-0022', 'sdaffadsd', 5, 'fsddfs', 'baik', 'dipinjam', 243.00, '2025-10-08', 'AST_68fcf5e586ee83.47065229.jpg', '', '2025-10-25 23:08:09', '2025-10-25 16:08:05', '2025-10-26 10:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Elektronik', 'Perangkat elektronik kampus seperti laptop, proyektor, dll.'),
(2, 'Furniture', 'Meja, kursi, dan lemari kantor.'),
(3, 'Kendaraan', 'Kendaraan operasional kampus.'),
(4, 'Peralatan Lab', 'Peralatan praktikum di laboratorium.'),
(5, 'Bangunan', 'Gedung dan ruangan kampus.');

-- --------------------------------------------------------

--
-- Table structure for table `damage_reports`
--

CREATE TABLE `damage_reports` (
  `id_laporan` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_aset` int(11) DEFAULT NULL,
  `tanggal_lapor` date DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('baru','diproses','selesai') DEFAULT 'baru'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id_peminjaman` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_aset` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected','returned') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id_peminjaman`, `id_user`, `id_aset`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, NULL, 11, '2025-10-25', '2025-10-26', 'rejected', '2025-10-25 14:04:07'),
(2, NULL, 6, '2025-10-26', '2025-10-31', 'rejected', '2025-10-25 14:08:01'),
(3, 19, 11, '2025-10-26', '2025-10-26', 'approved', '2025-10-25 14:25:17'),
(4, 19, 26, '2025-10-01', '2025-11-07', 'approved', '2025-10-26 10:56:02'),
(5, 19, 27, '2025-10-14', '2025-10-28', 'approved', '2025-10-26 10:57:57'),
(6, 19, 11, '2025-10-27', '2025-10-30', 'approved', '2025-10-26 12:01:36'),
(7, 37, 19, '2025-10-27', '2025-10-31', 'approved', '2025-10-26 12:05:22'),
(8, 19, 6, '2025-10-29', '2025-11-08', 'pending', '2025-10-26 12:16:12'),
(9, 19, 8, '2025-10-30', '2025-10-15', 'approved', '2025-10-26 12:16:41');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_history`
--

CREATE TABLE `maintenance_history` (
  `id_history` int(11) NOT NULL,
  `id_aset` int(11) DEFAULT NULL,
  `tanggal_perawatan` date DEFAULT NULL,
  `biaya` decimal(12,2) DEFAULT NULL,
  `teknisi` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_aset_setelah_perawatan` enum('baik','rusak','hilang') DEFAULT 'baik'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_schedule`
--

CREATE TABLE `maintenance_schedule` (
  `id_jadwal` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `id_aset` int(11) DEFAULT NULL,
  `tanggal_jadwal` date DEFAULT NULL,
  `status` enum('terjadwal','selesai','dibatalkan') DEFAULT 'terjadwal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','pengguna','manajemen') DEFAULT 'pengguna',
  `no_telp` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `no_telp`, `foto`, `status`, `deleted_at`) VALUES
(17, 'Sukro', 'sukro@siamka.com', '$2y$10$yQG9TxpKZE4NUuFMy7IiH.0yniGIHEVBEaRX97JLDo70yS6W5lN1m', 'admin', '081442343845', '1761475599_WIN_20240622_10_05_40_Pro.jpg', 'aktif', NULL),
(18, 'sulis', 'sulis@siamka.com', '$2y$10$G7v8XV5ziMgbDZ87.wscAeDiKTYFWmN00ByerMB1WJLRbS0BHVbUO', 'manajemen', '081443239444', '1761477873_1.jpg', 'aktif', NULL),
(19, 'Bagus', 'bagus@siamka.com', '$2y$10$tAYqJWi5Ww3wpnzoufhcB.b72CS7GYCYy.lMkPP6fks1WNzWTMCgW', 'pengguna', '081332482333', '1761410363_2.jpg', 'aktif', NULL),
(27, 'fsadfdsafdfd', 'sdaffdfad@fdas', '$2y$10$kaRIFDUzxResPHQDqElRtOwebzYNKjXykG.PJSGgmdB3ThDCaXsjm', 'pengguna', 'dsfdfsfsd', NULL, 'aktif', '2025-10-19 23:08:40'),
(28, 'fsadfdsa', 'bagus@gmail.com', '$2y$10$2H0FDlmpxuBAG0zhzEB/guuFVmKizX7aViWz6EjIzc6Tg/S6VqMFO', 'manajemen', 'fsdfsdsd', NULL, 'aktif', '2025-10-19 23:09:15'),
(29, 'fsdsffas', 'sdfafdsfdfddf@gmailc', '$2y$10$eLLLBYvimSY/CIBHda8LmOGY7u8kz5Mzipr0x7nax5rw2AgO8PoMC', 'manajemen', '242233', NULL, 'aktif', '2025-10-19 23:29:31'),
(30, 'adsfdfsads', 'afdfddfsfd2gmg@sad', '$2y$10$BNxDWEqQ7QnCa8sJiZ8te.yKH8yJkx3vURTjuX9ZgiGtD0vo7HOiu', 'pengguna', 'fdsfdsfds', NULL, 'aktif', '2025-10-19 23:41:28'),
(31, 'fadsdfsdf', 'dffdsfdssdfsd@ss', '$2y$10$G7VHkNEM4nbIwXOFDE/7M.AO7A4iv/zMyPvyw/G3jfIVHVv1SbxT6', 'admin', '223232332', NULL, 'aktif', '2025-10-19 23:44:29'),
(32, 'surtidds2w', 'surti@gmail.com', '$2y$10$InM5EtzKQat3DvCZQXrSU.Np.ah76wpLqW4z5Cb2tIF9yJmQVWf9W', 'admin', '08122343211', NULL, 'aktif', '2025-10-20 20:01:39'),
(33, 'fadssdfd', 'hsfdids@ndsdsf', '$2y$10$iD9ymU5nX.InheqHVsluWOagqrs/a4feJKmaUCVhW2aews5u3hX86', 'admin', '9080909', NULL, 'aktif', '2025-10-20 19:55:08'),
(34, 'Bagus', 'bagus@jsdfoidjsdo', '$2y$10$W1NfeL0yHSSDW5VQt0pPU..hddp/ZOK.ZDeHkmNYoIpVnayzzS4pS', 'admin', '081222233', NULL, 'aktif', '2025-10-20 20:01:43'),
(35, 'alif', 'alif@siamka.com', '$2y$10$lRShFFSjHd3sZ4AagpYc6.zrnpMxp5zrep5vDrO92qDNIh0NH4FAi', 'admin', '08122332322', NULL, 'aktif', '2025-10-20 20:16:46'),
(36, 'ssddsd', 'adsfdsfdsfdsf@dsfdfsd', '$2y$10$GHVYg6rTrT/DMLGUUheb8eLA5SpKxT/3vCFqWR76Wp6nTVo/p4Pn6', 'pengguna', '324243', NULL, 'aktif', '2025-10-25 22:40:56'),
(37, 'alif', 'alif123@siamka.com', '$2y$10$8jPyQvVVWhxXwcXzKtGto.nZQvGcCVB/PKnbwCUdhLZYMLc0DHije', 'pengguna', '081332129234', NULL, 'aktif', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id_aset`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `damage_reports`
--
ALTER TABLE `damage_reports`
  ADD PRIMARY KEY (`id_laporan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_aset` (`id_aset`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_aset` (`id_aset`);

--
-- Indexes for table `maintenance_history`
--
ALTER TABLE `maintenance_history`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `id_aset` (`id_aset`);

--
-- Indexes for table `maintenance_schedule`
--
ALTER TABLE `maintenance_schedule`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_aset` (`id_aset`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id_aset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `damage_reports`
--
ALTER TABLE `damage_reports`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `maintenance_history`
--
ALTER TABLE `maintenance_history`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `maintenance_schedule`
--
ALTER TABLE `maintenance_schedule`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `fk_assets_category` FOREIGN KEY (`id_kategori`) REFERENCES `categories` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `damage_reports`
--
ALTER TABLE `damage_reports`
  ADD CONSTRAINT `fk_damage_asset` FOREIGN KEY (`id_aset`) REFERENCES `assets` (`id_aset`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_damage_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `fk_loans_asset` FOREIGN KEY (`id_aset`) REFERENCES `assets` (`id_aset`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loans_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `maintenance_history`
--
ALTER TABLE `maintenance_history`
  ADD CONSTRAINT `fk_history_asset` FOREIGN KEY (`id_aset`) REFERENCES `assets` (`id_aset`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `maintenance_schedule`
--
ALTER TABLE `maintenance_schedule`
  ADD CONSTRAINT `fk_schedule_admin` FOREIGN KEY (`id_admin`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_schedule_asset` FOREIGN KEY (`id_aset`) REFERENCES `assets` (`id_aset`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
