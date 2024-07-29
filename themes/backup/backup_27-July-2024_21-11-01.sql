#
# TABLE STRUCTURE FOR: tb_kegiatan
#

DROP TABLE IF EXISTS `tb_kegiatan`;

CREATE TABLE `tb_kegiatan` (
  `id_kegiatan` varchar(15) NOT NULL,
  `nama_kegiatan` varchar(100) NOT NULL,
  `tahun` year(4) NOT NULL,
  `status` enum('ON','OFF') NOT NULL,
  PRIMARY KEY (`id_kegiatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: tb_level
#

DROP TABLE IF EXISTS `tb_level`;

CREATE TABLE `tb_level` (
  `id_level` varchar(2) NOT NULL,
  `level` varchar(15) NOT NULL,
  PRIMARY KEY (`id_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('1', 'superadmin');
INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('2', 'admin');
INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('3', 'user');
INSERT INTO `tb_level` (`id_level`, `level`) VALUES ('4', 'public');


#
# TABLE STRUCTURE FOR: tb_pemasukan
#

DROP TABLE IF EXISTS `tb_pemasukan`;

CREATE TABLE `tb_pemasukan` (
  `id_pemasukan` varchar(15) NOT NULL,
  `jenis_pemasukan` varchar(100) NOT NULL,
  `nominal` varchar(20) NOT NULL,
  `buktI_transfer` text NOT NULL,
  `tgl_pemasukan` date NOT NULL,
  `id_kegiatan` varchar(15) NOT NULL,
  PRIMARY KEY (`id_pemasukan`),
  KEY `id_kegiatan` (`id_kegiatan`),
  CONSTRAINT `tb_pemasukan_ibfk_1` FOREIGN KEY (`id_kegiatan`) REFERENCES `tb_kegiatan` (`id_kegiatan`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: tb_pengaturan
#

DROP TABLE IF EXISTS `tb_pengaturan`;

CREATE TABLE `tb_pengaturan` (
  `id_pengaturan` varchar(7) NOT NULL,
  `nama_judul` varchar(50) NOT NULL,
  `meta_keywords` text NOT NULL,
  `meta_description` text NOT NULL,
  `background` text NOT NULL,
  PRIMARY KEY (`id_pengaturan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pengaturan` (`id_pengaturan`, `nama_judul`, `meta_keywords`, `meta_description`, `background`) VALUES ('P1xhDwL', 'Si-Periksa', 'Sistem Antrian Periksa Dokter', 'Si-Periksa merupakan web app untuk melayani antrian periksa pasien secara online', 'header_656f3421970de.jpg');


#
# TABLE STRUCTURE FOR: tb_pengeluaran
#

DROP TABLE IF EXISTS `tb_pengeluaran`;

CREATE TABLE `tb_pengeluaran` (
  `id_pengeluaran` varchar(15) NOT NULL,
  `jenis_pengeluaran` varchar(255) NOT NULL,
  `nominal` varchar(20) NOT NULL,
  `bukti_nota` text NOT NULL,
  `tgl_pengeluaran` date NOT NULL,
  `id_kegiatan` varchar(15) NOT NULL,
  PRIMARY KEY (`id_pengeluaran`),
  KEY `id_kegiatan` (`id_kegiatan`),
  CONSTRAINT `tb_pengeluaran_ibfk_1` FOREIGN KEY (`id_kegiatan`) REFERENCES `tb_kegiatan` (`id_kegiatan`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#
# TABLE STRUCTURE FOR: tb_pengguna
#

DROP TABLE IF EXISTS `tb_pengguna`;

CREATE TABLE `tb_pengguna` (
  `id_pengguna` varchar(15) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `keterangan` varchar(25) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `foto_profile` text NOT NULL,
  `id_level` varchar(2) NOT NULL,
  PRIMARY KEY (`id_pengguna`),
  KEY `id_level` (`id_level`),
  CONSTRAINT `tb_pengguna_ibfk_1` FOREIGN KEY (`id_level`) REFERENCES `tb_level` (`id_level`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A001bnHDs', 'Erik W', '081456141227', 'Ini admin', 'erik@gmail.com', '202cb962ac59075b964b07152d234b70', 'profile_658bb959385e8.jpeg', '1');
INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A0025Iu6U0', 'Rani', '282762', 'Admin', 'rani@gmail.com', '202cb962ac59075b964b07152d234b70', '', '2');
INSERT INTO `tb_pengguna` (`id_pengguna`, `nama`, `no_hp`, `keterangan`, `email`, `password`, `foto_profile`, `id_level`) VALUES ('A003rulfBY', 'Ani', '87373651', 'Perawat', 'ani@gmail.com', '202cb962ac59075b964b07152d234b70', '', '2');


