-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2024 at 10:20 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sirepository`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id_berita` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `isi_berita` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `foto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id_berita`, `judul`, `isi_berita`, `tanggal`, `foto`) VALUES
(50, 'Pengumuman Pelaksanaan PKL 2023/2024', 'Kembang waru merupakan roti berbentuk bunga yang diproduksi dengan metode tradisional. Metode yang digunakan secara turun-temurun tersebut menjadikan kembang waru memiliki cita rasa yang khas. Kembang waru terbuat dari tepung terigu, telur ayam, gula, susu, vanili, dan mentega. Bahan-bahan tersebut dicampur menjadi adonan lalu dimasukkan ke dalam cetakan berbentuk bunga waru yang sudah dioles mentega. Adonan tersebut kemudian dipanggang di oven kuno yang masih menggunakan arang sebagai bahan bakarnya. Kembang waru dipercaya sebagai warisan Kerajaan Mataram Islam. Mengutip laman Dinas Kebudayaan DIY, tidak diketahui secara persis siapa penemu roti khas Kotagede tersebut. Namun, pada zaman Mataram Islam, kembang waru selalu menjadi hidangan favorit yang selalu ada dalam setiap hajatan ataupun acara adat. Kembang waru dibuat oleh para sahabat keraton pada zaman dulu karena di Kotagede banyak terdapat pohon waru, selain itu bentuk bunga waru lebih mudah ditiru jika dibandingkan dengan bunga mawar atau bunga kenanga. Awalnya, kembang waru hanya boleh dikonsumsi oleh para bangsawan dan keluarga kerajaan pada acara-acara tertentu di kerajaan. Tetapi seiring berkembangnya zaman, kue ini dapat dinikmati oleh berbagai kalangan masyarakat. Kembang waru berbentuk bunga waru yang memiliki delapan sisi. Bentuk kembang tersebut memiliki makna delapan laku seorang pemimpin yang merupakan personifikasi dari delapan elemen unsur alam yakni tanah, air, angin, api, matahari, bulan, bintang dan langit. Jika seorang pemimpin mampu menerapkan delapan laku tersebut, maka ia akan menjadi pemimpin yang berwibawa dan mampu mengayomi semua rakyatnya. Selain itu, terdapat pengharapan bagi siapapun yang memakan kembang waru dimana ia diharapkan akan selalu mengingat nasihat leluhur sehingga dapat menjalani kehidupan dengan penuh penghargaan', '2024-01-20', '291-download.jpg'),
(57, 'Pengumuman Pelaksanaan Monitoring PKL', 'Kembang waru merupakan roti berbentuk bunga yang diproduksi dengan metode tradisional. Metode yang digunakan secara turun-temurun tersebut menjadikan kembang waru memiliki cita rasa yang khas. Kembang waru terbuat dari tepung terigu, telur ayam, gula, susu, vanili, dan mentega. Bahan-bahan tersebut dicampur menjadi adonan lalu dimasukkan ke dalam cetakan berbentuk bunga waru yang sudah dioles mentega. Adonan tersebut kemudian dipanggang di oven kuno yang masih menggunakan arang sebagai bahan bakarnya. Kembang waru dipercaya sebagai warisan Kerajaan Mataram Islam. Mengutip laman Dinas Kebudayaan DIY, tidak diketahui secara persis siapa penemu roti khas Kotagede tersebut. Namun, pada zaman Mataram Islam, kembang waru selalu menjadi hidangan favorit yang selalu ada dalam setiap hajatan ataupun acara adat. Kembang waru dibuat oleh para sahabat keraton pada zaman dulu karena di Kotagede banyak terdapat pohon waru, selain itu bentuk bunga waru lebih mudah ditiru jika dibandingkan dengan bunga mawar atau bunga kenanga. Awalnya, kembang waru hanya boleh dikonsumsi oleh para bangsawan dan keluarga kerajaan pada acara-acara tertentu di kerajaan. Tetapi seiring berkembangnya zaman, kue ini dapat dinikmati oleh berbagai kalangan masyarakat. Kembang waru berbentuk bunga waru yang memiliki delapan sisi. Bentuk kembang tersebut memiliki makna delapan laku seorang pemimpin yang merupakan personifikasi dari delapan elemen unsur alam yakni tanah, air, angin, api, matahari, bulan, bintang dan langit. Jika seorang pemimpin mampu menerapkan delapan laku tersebut, maka ia akan menjadi pemimpin yang berwibawa dan mampu mengayomi semua rakyatnya. Selain itu, terdapat pengharapan bagi siapapun yang memakan kembang waru dimana ia diharapkan akan selalu mengingat nasihat leluhur sehingga dapat menjalani kehidupan dengan penuh penghargaan', '2024-01-25', '2106976916_Free Photo _ Multiracial group of young creative people in smart casual wear discussing business brainstorming meeting ideas mobile application software design project in modern office_.jpg'),
(58, 'kurikulum', 'klklkl', '2024-01-25', '350-WhatsApp Image 2023-12-01 at 09.04.58.jpeg'),
(61, 'feki', 'oke', '2024-03-06', '1113903354_WhatsApp Image 2024-02-10 at 09.42.52.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id_dokumen` int(11) NOT NULL,
  `id_pkl` int(11) DEFAULT NULL,
  `judul_dokumen` varchar(255) DEFAULT NULL,
  `Dokumen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `id_pkl`, `judul_dokumen`, `Dokumen`) VALUES
(28, NULL, 'surat izin orang tua', '2025558119_Pendaftaran Sidang Skripsi Genap 2023-2024.pdf'),
(30, NULL, 'surat izin orang tua', '1217932_Berkas Pasca Ujian Skripsi Genap 2023-3024.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `guru_pamong`
--

CREATE TABLE `guru_pamong` (
  `id_guru` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `NIP` int(11) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Alamat` text DEFAULT NULL,
  `Foto` text DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `guru_pamong`
--

INSERT INTO `guru_pamong` (`id_guru`, `nama`, `NIP`, `Email`, `Alamat`, `Foto`, `no_telp`) VALUES
(10, 'Ibu Guru', 54321, 'ibuguru@gmail.com', 'ponorogo', '2004486339_WhatsApp Image 2023-01-25 at 11.06.07.jpeg', '1234554321'),
(11, 'Bapak Guru', 98799, 'bapakguru@gmail.com', 'Arosbaya', '156-484_400+ 3d Person Fotografías de stock, fotos e imágenes libres de derechos.jpg', '4321');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_pkl`
--

CREATE TABLE `laporan_pkl` (
  `id_laporan` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `tanggal_kumpul` date DEFAULT NULL,
  `judul_laporan` varchar(255) NOT NULL,
  `berkas` text DEFAULT NULL,
  `google_drive_file_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `laporan_pkl`
--

INSERT INTO `laporan_pkl` (`id_laporan`, `id_siswa`, `tanggal_kumpul`, `judul_laporan`, `berkas`, `google_drive_file_id`) VALUES
(163, 33, '2024-02-26', 'Laporan Praktik Kerja Lapangan di Avichena', 'admin/Laporan PKL/salinan 183 kalender akademik.pdf', '1kljKvl2J3H4ttT5uO1b2qfNglNPPZs7L'),
(165, 29, '2024-03-04', 'Laporan Praktik Kerja Lapangan di Avichena', 'admin/Laporan PKL/Daftar-PKL-Siswa.pdf', '1LpwP4AtlrViSiRm7KcpMXmXu4HPO0DjY'),
(166, 56, '2024-03-13', 'Laporan Praktik Kerja Lapangan di KOMINFO', 'Daftar-PKL-Siswa.pdf', '1xBpjsiniTVjjW4xalgsWrK8mcmG3o_Es');

-- --------------------------------------------------------

--
-- Table structure for table `logbook`
--

CREATE TABLE `logbook` (
  `id_logbook` int(11) NOT NULL,
  `id_pkl` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `status_logbook` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logbook`
--

INSERT INTO `logbook` (`id_logbook`, `id_pkl`, `id_siswa`, `tanggal`, `aktivitas`, `status_logbook`) VALUES
(96, 84, 56, '2024-03-11', 'bismillah', 'ok'),
(110, 84, 56, '2024-04-02', 'oke', 'bagus'),
(111, 84, 56, '2024-03-27', 'ini siswa', NULL),
(112, 84, 56, '2024-03-12', 'Bismillah', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mitra`
--

CREATE TABLE `mitra` (
  `id_mitra` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kontak` varchar(20) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mitra`
--

INSERT INTO `mitra` (`id_mitra`, `nama`, `alamat`, `kontak`, `deskripsi`, `foto`) VALUES
(21, 'O.K Zada Digital Printing', 'Jl. KH.Zainal Alim No.31, Kemayoran Bangkalan', '00', NULL, '../gambar/foto-5.JPG'),
(29, 'Azheel Computer', 'Mlajah Kec. Bangkalan', '000', NULL, '../gambar/foto-1.JPG'),
(30, 'TIC NET', 'Jl. Ki Lemah Duwur No.42c, Sumur Kembang Pejangan Bangkalan', '000', NULL, '../gambar/Free Photo _ Multiracial group of young creative people in smart casual wear discussing business brainstorming meeting ideas mobile application software design project in modern office_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_pkl`
--

CREATE TABLE `nilai_pkl` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `nilai` int(11) DEFAULT NULL,
  `grade` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nilai_pkl`
--

INSERT INTO `nilai_pkl` (`id_nilai`, `id_siswa`, `nilai`, `grade`, `file`) VALUES
(33, 29, 88, 'A', '245264754_Cetak Kartu Hasil Studi - Portal Akademik.pdf'),
(39, 55, 90, 'B', '2103543666_salinan 183 kalender akademik.pdf'),
(40, NULL, 80, 'B', '698784453_Pendaftaran Sidang Skripsi Genap 2023-2024.pdf'),
(41, NULL, 90, 'B', '677787208_timeline.pdf'),
(42, 56, 90, 'B', '1676359229_timeline.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `pkl`
--

CREATE TABLE `pkl` (
  `id_pkl` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `nama_perusahaan` varchar(255) DEFAULT NULL,
  `tahun_pelajaran` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pkl`
--

INSERT INTO `pkl` (`id_pkl`, `id_siswa`, `tgl_mulai`, `tgl_selesai`, `kelas`, `nama_perusahaan`, `tahun_pelajaran`) VALUES
(77, 33, '2024-02-21', '2024-03-09', 'XI A', 'percetakan', 2023),
(79, 29, '2024-03-04', '2024-03-11', 'XI', 'percetakan', 2023),
(84, 56, '2024-03-10', '2024-03-10', 'XI A', 'percetakan', 2024);

-- --------------------------------------------------------

--
-- Table structure for table `sertifikat`
--

CREATE TABLE `sertifikat` (
  `id_sertifikat` int(11) NOT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `file_sertifikat` text DEFAULT NULL,
  `google_drive` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sertifikat`
--

INSERT INTO `sertifikat` (`id_sertifikat`, `id_siswa`, `file_sertifikat`, `google_drive`) VALUES
(9, 29, 'batikmadura.jpg', '1quv8Dgo77XECHB_j7C4FoDv8WAEo_Rrl'),
(19, 33, 'sertifikat_feki.png', '1iffugCIjCeHRVRlXindwe4Flzb8QU03j'),
(25, 55, 'whatsapp_image_2024_02_10_at_09.55.31.jpeg', '1sMqyFu2516LIqUhoYJGLpbrT4dA8Bmzy');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_nilai` int(11) DEFAULT NULL,
  `id_pamong` int(11) DEFAULT NULL,
  `Nama_siswa` varchar(255) NOT NULL,
  `NIS` int(11) DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `foto` text DEFAULT NULL,
  `jenis_kelamin` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_hp` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `id_user`, `id_nilai`, `id_pamong`, `Nama_siswa`, `NIS`, `kelas`, `foto`, `jenis_kelamin`, `alamat`, `tanggal_lahir`, `no_hp`, `email`) VALUES
(29, 39, NULL, NULL, 'Feki Dui Marinda', 987455, 'XI B', NULL, 'Perempuan', 'Ponorogo Jawa Timur', '2004-02-13', 2147483647, 'kamu4657@gmail.com'),
(33, 46, NULL, NULL, 'feki marinda', 242345, 'XI A', NULL, 'Laki-laki', 'bangkalan', '2011-02-25', 12345651, 'fekimarinda2901@gmail.com'),
(54, 97, NULL, NULL, 'Feki Dui Marinda', 111, 'XI B', NULL, 'Perempuan', 'bangkalan', '2024-03-06', 123456, ''),
(55, 93, NULL, NULL, 'mawar', 12345, 'XI A', NULL, 'Perempuan', 'sendang', '2024-03-06', 6, ''),
(56, 99, NULL, NULL, 'siswa', 24234, 'XI B', NULL, 'Perempuan', 'ponorogo', '2024-03-09', 123456, 'kamu4657@gmail.com'),
(57, 100, NULL, NULL, 'siswi', 123, '1A', NULL, 'Perempuan', 'Arosbaya, Bangkalan', '2024-03-12', 2147483647, 'fekimarinda2901@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) DEFAULT NULL,
  `status` enum('admin','siswa','guru') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `status`) VALUES
(39, 'fekimarinda', '123', 'admin'),
(46, 'feki', '123', 'siswa'),
(77, 'admin', 'admin', 'guru'),
(79, 'feki', 'feki', 'admin'),
(80, 'fekimarinda', '1235', 'admin'),
(87, 'admin', 'admin', 'admin'),
(89, 'halomawar', '123', 'admin'),
(92, 'admin', 'admin', 'admin'),
(93, '200631100063', '123', 'admin'),
(97, 'Feki Marinda', '123', 'admin'),
(98, 'admin', 'admin', 'admin'),
(99, 'siswa', '123', 'siswa'),
(100, 'siswi', '123', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_pkl` (`id_pkl`);

--
-- Indexes for table `guru_pamong`
--
ALTER TABLE `guru_pamong`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indexes for table `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  ADD PRIMARY KEY (`id_laporan`),
  ADD UNIQUE KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `logbook`
--
ALTER TABLE `logbook`
  ADD PRIMARY KEY (`id_logbook`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_pkl` (`id_pkl`) USING BTREE;

--
-- Indexes for table `mitra`
--
ALTER TABLE `mitra`
  ADD PRIMARY KEY (`id_mitra`);

--
-- Indexes for table `nilai_pkl`
--
ALTER TABLE `nilai_pkl`
  ADD PRIMARY KEY (`id_nilai`),
  ADD UNIQUE KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `pkl`
--
ALTER TABLE `pkl`
  ADD PRIMARY KEY (`id_pkl`) USING BTREE,
  ADD UNIQUE KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD PRIMARY KEY (`id_sertifikat`),
  ADD UNIQUE KEY `id_siswa` (`id_siswa`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `id_user` (`id_user`),
  ADD KEY `id_nilai` (`id_nilai`),
  ADD KEY `id_pamong` (`id_pamong`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `guru_pamong`
--
ALTER TABLE `guru_pamong`
  MODIFY `id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  MODIFY `id_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `logbook`
--
ALTER TABLE `logbook`
  MODIFY `id_logbook` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `mitra`
--
ALTER TABLE `mitra`
  MODIFY `id_mitra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `nilai_pkl`
--
ALTER TABLE `nilai_pkl`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `pkl`
--
ALTER TABLE `pkl`
  MODIFY `id_pkl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `sertifikat`
--
ALTER TABLE `sertifikat`
  MODIFY `id_sertifikat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `dokumen_ibfk_1` FOREIGN KEY (`id_pkl`) REFERENCES `pkl` (`id_pkl`);

--
-- Constraints for table `laporan_pkl`
--
ALTER TABLE `laporan_pkl`
  ADD CONSTRAINT `laporan_pkl_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Constraints for table `logbook`
--
ALTER TABLE `logbook`
  ADD CONSTRAINT `logbook_ibfk_2` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `logbook_ibfk_3` FOREIGN KEY (`id_pkl`) REFERENCES `pkl` (`id_pkl`);

--
-- Constraints for table `nilai_pkl`
--
ALTER TABLE `nilai_pkl`
  ADD CONSTRAINT `nilai_pkl_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Constraints for table `pkl`
--
ALTER TABLE `pkl`
  ADD CONSTRAINT `pkl_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Constraints for table `sertifikat`
--
ALTER TABLE `sertifikat`
  ADD CONSTRAINT `sertifikat_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_ibfk_4` FOREIGN KEY (`id_nilai`) REFERENCES `nilai_pkl` (`id_nilai`),
  ADD CONSTRAINT `siswa_ibfk_5` FOREIGN KEY (`id_pamong`) REFERENCES `guru_pamong` (`id_guru`),
  ADD CONSTRAINT `siswa_ibfk_8` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
