-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for myplanner
CREATE DATABASE IF NOT EXISTS `myplanner` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `myplanner`;

-- Dumping structure for table myplanner.planner
CREATE TABLE IF NOT EXISTS `planner` (
  `id_planner` int NOT NULL,
  `id_theme` int NOT NULL,
  `id_task` int NOT NULL,
  `to_do` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_planner`),
  KEY `id_theme` (`id_theme`),
  KEY `id_task` (`id_task`),
  CONSTRAINT `planner_ibfk_1` FOREIGN KEY (`id_theme`) REFERENCES `theme` (`id_theme`),
  CONSTRAINT `planner_ibfk_2` FOREIGN KEY (`id_task`) REFERENCES `task` (`id_task`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table myplanner.planner: ~3 rows (approximately)
INSERT INTO `planner` (`id_planner`, `id_theme`, `id_task`, `to_do`, `status`) VALUES
	(192344, 645224, 631512, 'Today', 0),
	(736822, 748291, 216734, 'Today', 0),
	(864254, 135716, 534171, 'Completed', 1);

-- Dumping structure for table myplanner.task
CREATE TABLE IF NOT EXISTS `task` (
  `id_task` int NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `due_date` date NOT NULL,
  `reminder_at` time NOT NULL,
  `repeat_on` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_task`),
  CONSTRAINT `task_chk_1` CHECK ((`repeat_on` in (_utf8mb4'Sun',_utf8mb4'Mon',_utf8mb4'Tue',_utf8mb4'Wed',_utf8mb4'Thu',_utf8mb4'Fri',_utf8mb4'Sat')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table myplanner.task: ~0 rows (approximately)
INSERT INTO `task` (`id_task`, `title`, `description`, `category`, `due_date`, `reminder_at`, `repeat_on`) VALUES
	(216734, 'Dinner Big Family', 'Makan dengan keluarga', 'Personal', '2023-12-09', '19:00:00', 'Sun'),
	(534171, 'Project Praktikum PBO', 'Jangan lupa submit kode', 'College', '2023-12-10', '23:59:00', 'Mon'),
	(631512, 'Olahraga Pagi', 'Olahraga setelah cape buat dokumen', 'Work', '2023-12-10', '06:00:00', 'Mon');

-- Dumping structure for table myplanner.theme
CREATE TABLE IF NOT EXISTS `theme` (
  `id_theme` int NOT NULL,
  `color` varchar(50) NOT NULL,
  `texture` varchar(50) NOT NULL,
  `background` varchar(255) NOT NULL,
  PRIMARY KEY (`id_theme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table myplanner.theme: ~4 rows (approximately)
INSERT INTO `theme` (`id_theme`, `color`, `texture`, `background`) VALUES
	(135716, '#B3D4DE', 'Pink Marble', 'Palm Beach Sunset'),
	(221938, '#54793E', 'Blue Marble', 'Field in Summer'),
	(645224, '#EE89A9', 'Green Marble', 'River and Mountain'),
	(748291, '#F8C93B', 'Grey Marble', 'Sunrise Into Water');

-- Dumping structure for table myplanner.user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL,
  `id_planner` int NOT NULL,
  `photo` blob,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(15) NOT NULL,
  `complete_task` int DEFAULT NULL,
  `pending_task` int DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  KEY `id_planner` (`id_planner`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_planner`) REFERENCES `planner` (`id_planner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table myplanner.user: ~3 rows (approximately)
INSERT INTO `user` (`id_user`, `id_planner`, `photo`, `name`, `email`, `password`, `complete_task`, `pending_task`) VALUES
	(653129, 192344, _binary 0x70686f746f7075616e2e6a7067, 'Puan', 'puan584@gmail.com', 'password123', 1, 1),
	(927361, 864254, _binary 0x70686f746f6e61736879612e6a7067, 'Nashya', 'nashya111@gmail.com', 'pasword445', 2, 1),
	(938162, 736822, _binary 0x70686f746f6c75746866692e6a7067, 'Luthfiyyah', 'luthfi295@gmail.com', 'password782', 1, 2);

-- Dumping structure for table myplanner.widget
CREATE TABLE IF NOT EXISTS `widget` (
  `id_widget` int NOT NULL,
  `id_planner` int NOT NULL,
  `opacity` int NOT NULL,
  `size` enum('Normal','Large','Huge') DEFAULT NULL,
  `time_scope` enum('All Tasks','Today') DEFAULT NULL,
  `category_filter` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_widget`),
  KEY `id_planner` (`id_planner`),
  CONSTRAINT `widget_ibfk_1` FOREIGN KEY (`id_planner`) REFERENCES `planner` (`id_planner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table myplanner.widget: ~0 rows (approximately)
INSERT INTO `widget` (`id_widget`, `id_planner`, `opacity`, `size`, `time_scope`, `category_filter`) VALUES
	(409217, 736822, 70, 'Huge', 'All Tasks', 'Personal'),
	(721633, 192344, 80, 'Normal', 'All Tasks', 'Work'),
	(891721, 864254, 90, 'Large', 'Today', 'College');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
