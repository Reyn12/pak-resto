-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2024 at 01:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rpl`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminresto`
--

CREATE TABLE `adminresto` (
  `idAdmin` varchar(6) NOT NULL,
  `namaAdmin` varchar(50) NOT NULL,
  `passwordAdmin` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminresto`
--

INSERT INTO `adminresto` (`idAdmin`, `namaAdmin`, `passwordAdmin`) VALUES
('ASC001', 'Supratman', 'Supratman001');

-- --------------------------------------------------------

--
-- Table structure for table `kasir`
--

CREATE TABLE `kasir` (
  `idKasir` varchar(6) NOT NULL,
  `namaKasir` varchar(50) NOT NULL,
  `passwordKasir` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kasir`
--

INSERT INTO `kasir` (`idKasir`, `namaKasir`, `passwordKasir`) VALUES
('CSC001', 'Melia Sejahtera', 'Melia001'),
('CSC002', 'Dudu', 'Dudu002');

-- --------------------------------------------------------

--
-- Table structure for table `koki`
--

CREATE TABLE `koki` (
  `idKoki` varchar(6) NOT NULL,
  `namaKoki` varchar(50) NOT NULL,
  `passwordKoki` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `koki`
--

INSERT INTO `koki` (`idKoki`, `namaKoki`, `passwordKoki`) VALUES
('KSC001', 'Andi Makmur', 'Andi001'),
('KSC002', 'Dani Hamdan', 'Dani002'),
('KSC003', 'Susi', 'Susi003');

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

CREATE TABLE `meja` (
  `noMeja` varchar(10) NOT NULL,
  `Nomor Meja` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`noMeja`, `Nomor Meja`) VALUES
('A01', '1'),
('A02', '2'),
('A03', '3'),
('B01', '4'),
('B02', '5'),
('B03', '6'),
('C01', '7'),
('C02', '8'),
('C03', '9'),
('', '');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `idMenu` varchar(8) NOT NULL,
  `jenis` varchar(20) NOT NULL,
  `namaMakananMinuman` varchar(50) NOT NULL,
  `harga` int(10) NOT NULL,
  `ketersediaan` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`idMenu`, `jenis`, `namaMakananMinuman`, `harga`, `ketersediaan`) VALUES
('DRK001', 'MINUMAN', 'Es Teh Manis', 5000, 20),
('DRK002', 'MINUMAN', 'Es Jeruk', 6000, 1),
('DRK003', 'MINUMAN', 'Es Kopi', 10000, 1),
('DRK004', 'MINUMAN', 'Es Coklat', 8000, 1),
('DRK005', 'MINUMAN', 'Es Lemon Tea', 7000, 1),
('MKN001', 'MAKANAN', 'Baso Urat', 20000, 1),
('MKN002', 'MAKANAN', 'Baso Cincang', 20000, 1),
('MKN003', 'MAKANAN', 'Baso Ikan', 20000, 1),
('MKN004', 'MAKANAN', 'Baso Beranak', 35000, 1),
('MKN005', 'MAKANAN', 'Baso Ayam', 15000, 1),
('MKN006', 'MAKANAN', 'Baso Aci', 10000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menumakanan`
--

CREATE TABLE `menumakanan` (
  `idMenu` varchar(8) NOT NULL,
  `namaMakanan` varchar(50) NOT NULL,
  `harga` int(10) NOT NULL,
  `ketersediaan` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menumakanan`
--

INSERT INTO `menumakanan` (`idMenu`, `namaMakanan`, `harga`, `ketersediaan`) VALUES
('MKN001', 'Baso Urat', 20000, 2),
('MKN002', 'Baso Cincang', 20000, 11),
('MKN003', 'Baso Ikan', 20000, 1),
('MKN004', 'Baso Beranak', 35000, 1),
('MKN005', 'Baso Ayam', 15000, 1),
('MKN007', 'Mie', 10000, 20);

-- --------------------------------------------------------

--
-- Table structure for table `menuminuman`
--

CREATE TABLE `menuminuman` (
  `idMenu` varchar(8) NOT NULL,
  `namaMinuman` varchar(50) NOT NULL,
  `harga` int(10) NOT NULL,
  `ketersediaan` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menuminuman`
--

INSERT INTO `menuminuman` (`idMenu`, `namaMinuman`, `harga`, `ketersediaan`) VALUES
('DRK002', 'Es Jeruk', 6000, 1),
('DRK003', 'Es Kopi', 10000, 1),
('DRK004', 'Es Coklat', 8000, 1),
('DRK005', 'Es Lemon Tea', 7000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `idPelanggan` varchar(8) NOT NULL,
  `namaPelanggan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`idPelanggan`, `namaPelanggan`) VALUES
('P0000001', 'Rahmat');

-- --------------------------------------------------------

--
-- Table structure for table `pelayan`
--

CREATE TABLE `pelayan` (
  `idPelayan` varchar(6) NOT NULL,
  `namaPelayan` varchar(50) NOT NULL,
  `passwordPelayan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pelayan`
--

INSERT INTO `pelayan` (`idPelayan`, `namaPelayan`, `passwordPelayan`) VALUES
('PSC001', 'Dudin Sarimukti', 'Dudin001'),
('PSC002', 'Melodi', 'Melodi002');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `idPesanan` int(8) NOT NULL,
  `idPelanggan` varchar(8) NOT NULL,
  `idMenu` varchar(8) NOT NULL,
  `jumlah` int(5) NOT NULL,
  `noMeja` varchar(3) NOT NULL,
  `tanggalPesanan` date NOT NULL,
  `totalHarga` int(10) NOT NULL,
  `statusPembayaran` varchar(50) NOT NULL,
  `statusPesanan` varchar(50) NOT NULL,
  `idKoki` varchar(6) NOT NULL,
  `idPelayan` varchar(6) NOT NULL,
  `idKasir` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`idPesanan`, `idPelanggan`, `idMenu`, `jumlah`, `noMeja`, `tanggalPesanan`, `totalHarga`, `statusPembayaran`, `statusPesanan`, `idKoki`, `idPelayan`, `idKasir`) VALUES
(1, 'Andi', 'MKN001', 2, 'A01', '2024-08-05', 40000, 'Lunas', 'Selesai', 'KSC001', 'PSC001', 'CSC001'),
(2, 'Samsul', 'MKN002', 1, 'A01', '2024-08-05', 20000, 'Lunas', 'Selesai', 'KSC001', 'PSC001', 'CSC001'),
(3, 'Dadang', 'MKN001', 2, 'A01', '2024-08-05', 40000, 'Lunas', 'Selesai', 'KSC001', 'PSC001', 'CSC001'),
(4, 'Deni', 'MKN002', 1, 'A01', '2024-08-05', 20000, 'Lunas', 'Selesai', 'KSC001', 'PSC001', 'CSC001'),
(60, 'Andi', 'MKN003', 1, 'A03', '2024-08-09', 20000, 'Lunas', 'Selesai', 'KSC001', 'PSC001', 'CSC001'),
(61, 'Andi', 'DRK002', 1, 'A03', '2024-08-09', 6000, 'Belum Dibayar', 'Selesai', 'KSC001', 'PSC001', 'CSC001'),
(64, 'Alfi', 'MKN003', 1, 'A01', '2024-08-09', 20000, 'Belum Dibayar', 'Selesai', 'KSC001', 'PSC001', 'CSC001'),
(65, 'Alfi', 'DRK004', 1, 'A01', '2024-08-09', 8000, 'Belum Dibayar', 'Selesai', 'KSC001', 'PSC001', 'CSC001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminresto`
--
ALTER TABLE `adminresto`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Indexes for table `kasir`
--
ALTER TABLE `kasir`
  ADD PRIMARY KEY (`idKasir`);

--
-- Indexes for table `koki`
--
ALTER TABLE `koki`
  ADD PRIMARY KEY (`idKoki`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`idMenu`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`idPelanggan`);

--
-- Indexes for table `pelayan`
--
ALTER TABLE `pelayan`
  ADD PRIMARY KEY (`idPelayan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`idPesanan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `idPesanan` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
