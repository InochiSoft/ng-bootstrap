CREATE DATABASE IF NOT EXISTS `dbng` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dbng`;

DROP TABLE IF EXISTS `tblkontak`;

CREATE TABLE `tblkontak` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pesan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;