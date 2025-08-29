-----------------------------------------------tao cac bang thuoc tinh bao gom khoa chinh va khoa phu---------------------------------------------------
-- Tạo bảng admin
CREATE TABLE `admin` (
  `admin_name` char(40) DEFAULT NULL,
  `email` char(40) NOT NULL,
  `pass` char(40) DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng airline
CREATE TABLE `airline` (
  `email` char(40) NOT NULL,
  `pass` char(40) DEFAULT NULL,
  `airline_name` char(40) DEFAULT NULL,
  `logo` char(40) DEFAULT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `airline_name` (`airline_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng airport
CREATE TABLE `airport` (
  `airport_id` int(11) NOT NULL AUTO_INCREMENT,
  `airport_name` char(40) DEFAULT NULL,
  PRIMARY KEY (`airport_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng booked
CREATE TABLE `booked` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flight_id` int(11) DEFAULT NULL,
  `customer_email` char(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flight_id` (`flight_id`),
  KEY `customer_email` (`customer_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng customer
CREATE TABLE `customer` (
  `first_name` char(40) DEFAULT NULL,
  `last_name` char(40) DEFAULT NULL,
  `customer_name` char(40) DEFAULT NULL,
  `email` char(40) NOT NULL,
  `phone` int(11) DEFAULT NULL,
  `gender` char(40) DEFAULT NULL,
  `pass` char(40) DEFAULT NULL,
  `id_card` char(40) DEFAULT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `id_card_unique` (`id_card`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng flight
CREATE TABLE `flight` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source_date` date DEFAULT NULL,
  `source_time` time DEFAULT NULL,
  `dest_date` date DEFAULT NULL,
  `dest_time` time DEFAULT NULL,
  `dep_airport` char(40) DEFAULT NULL,
  `arr_airport` char(40) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `flight_class` char(40) DEFAULT NULL,
  `airline_name` char(40) DEFAULT NULL,
  `dep_airport_id` int(11) DEFAULT NULL,
  `arr_airport_id` int(11) DEFAULT NULL,
  `airline_email` char(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dep_airport_id` (`dep_airport_id`),
  KEY `arr_airport_id` (`arr_airport_id`),
  KEY `airline_email` (`airline_email`),
  KEY `airline_name` (`airline_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tạo bảng payment
CREATE TABLE `payment` (
  `Name_Card` char(40) NOT NULL,
  `Date_Exp` date DEFAULT NULL,
  `Name_Owned` char(40) DEFAULT NULL,
  `CVC` char(5) DEFAULT NULL,
  PRIMARY KEY (`Name_Card`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--------------------------------------------------------noi bang khoa chinh phu voi nhau---------------------------------------------------
-- Thêm ràng buộc khóa ngoại cho bảng booked
ALTER TABLE `booked`
  ADD CONSTRAINT `booked_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booked_ibfk_2` FOREIGN KEY (`customer_email`) REFERENCES `customer` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Thêm ràng buộc khóa ngoại cho bảng flight
ALTER TABLE `flight`
  ADD CONSTRAINT `flight_ibfk_1` FOREIGN KEY (`dep_airport_id`) REFERENCES `airport` (`airport_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `flight_ibfk_2` FOREIGN KEY (`arr_airport_id`) REFERENCES `airport` (`airport_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `flight_ibfk_3` FOREIGN KEY (`airline_email`) REFERENCES `airline` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `flight_ibfk_4` FOREIGN KEY (`airline_name`) REFERENCES `airline` (`airline_name`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Thêm ràng buộc khóa ngoại cho bảng customer
ALTER TABLE `customer`
  ADD CONSTRAINT `id_card_fk` FOREIGN KEY (`id_card`) REFERENCES `payment` (`Name_Card`) ON DELETE CASCADE ON UPDATE CASCADE;
------------------------------------------------------------------nhap du liue--------------------------------------------------------------
INSERT INTO `admin` (`admin_name`, `email`, `pass`) VALUES
('a', 'a@a.com', 'a'),
('admin', 'admin@gmail.com', 'admin'),
('systemadmin', 'systemadmin@a.com', 'systemadmin');
INSERT INTO `airline` (`email`, `pass`, `airline_name`, `logo`) VALUES
('airasia@gmail.com', 'a', 'Air Asia', 'uploads/Air Asia.png'),
('aircanada@gmail.com', 'a', 'Air Canada', 'uploads/Air Canada.png'),
('airchina@gmail.com', 'a', 'Air China', 'uploads/Air China.png'),
('airindia@gmail.com', 'a', 'Air India', 'uploads/Air India.png'),
('americanairlines@gmail.com', 'a', 'American Airlines', 'uploads/American Airlines.png'),
('bimanbangladesh@gmail.com', 'a', 'Biman Bangladesh', 'uploads/biman bangladesh.png'),
('britishairways@gmail.com', 'a', 'British Airways', 'uploads/British Airways.png'),
('cathaydragon@gmail.com', 'a', 'Cathay Dragon', 'uploads/Cathay Dragon.png'),
('egyptAir@gmail.com', 'a', 'EgyptAir', 'uploads/EgyptAir.png'),
('emirates@gmail.com', 'a', 'Emirates', 'uploads/Emirates.png'),
('ethiopian@gmail.com', 'a', 'Ethiopian', 'uploads/Ethiopian.png'),
('hawaiianairlines@gmail.com', 'a', 'Hawaiian Airlines', 'uploads/Hawaiian Airlines.png'),
('japanairlines@gmail.com', 'a', 'Japan Airlines', 'uploads/Japan Airlines.png'),
('koreanair@gmail.com', 'a', 'Korean Air', 'uploads/Korean Air.png'),
('lufthansa@gmail.com', 'a', 'Lufthansa', 'uploads/Lufthansa.png'),
('mexicana@gmail.com', 'a', 'Mexicana', 'uploads/Mexicana.png'),
('Qatarairways@gmail.com', 'a', 'Qatar Airways', 'uploads/airline-logos-qatar.png'),
('ryanair@gmail.com', 'a', 'Ryanair', 'uploads/Ryanair.png'),
('sriLankanairlines@gmail.com', 'a', 'SriLankan Airlines', 'uploads/SriLankan Airlines.png'),
('swiss@gmail.com', 'a', 'Swiss', 'uploads/Swiss.png'),
('thaiairways@gmail.com', 'a', 'Thai Airways', 'uploads/Thai Airways.png'),
('turkishairlines@gmail.com', 'a', 'Turkish Airlines', 'uploads/Turkish Airlines.png');
INSERT INTO `airport` (`airport_id`, `airport_name`) VALUES
(17, ' Dhaka Airport'),
(18, 'Cox’s Bazar Airport'),
(19, 'Barisal Airport'),
(22, 'Shah Amanat International Airport'),
(23, 'Jessore Airport'),
(24, 'Shah Makhdum Airport'),
(25, 'Saidpur Airport'),
(26, 'Osmani International Airport'),
(27, 'Hazrat Shahjalal International Airport'),
(28, 'Ishwardi Airport'),
(29, 'Singapore Changi Airport'),
(30, 'Hamad International Airport'),
(31, 'Tokyo Haneda International Airport'),
(32, 'Incheon International Airport'),
(33, 'Instanbul Airport'),
(34, 'Zurich Airport'),
(35, 'Madrid Barajas Airport'),
(36, 'King Fahd International Airport'),
(37, 'Indira Gandhi International Airport'),
(38, 'Paris Charles de Gaulle Airport'),
(39, 'Heathrow Airport'),
(40, 'Istanbul Airport'),
(41, 'Los Angeles International Airport');
INSERT INTO `booked` (`id`, `flight_id`, `customer_email`) VALUES
(219, 35, 'hellomiskat@gmail.com'),
(231, 36, 'hellomiskat@gmail.com'),
(232, 35, 'shakib@gmail.com'),
(233, 36, 'shakib@gmail.com'),
(235, 37, 'shakib@gmail.com'),
(236, 36, 'asir@gmail.com');
INSERT INTO `customer` (`first_name`, `last_name`, `customer_name`, `email`, `phone`, `gender`, `pass`) VALUES
('Afifa', 'Hoque', 'afifa', 'afifa@gmail.com', 5841321, 'female', 'a'),
('Akib', 'Abdullah', 'Akib', 'akib@gmail.com', 21121, 'male', 'a'),
('Apurba', 'Kumar', 'apurba', 'apurba@gmail.com', 1465464, 'male', 'a'),
('Ashab', 'Asir', 'asir', 'asir@gmail.com', 22411255, 'male', 'a'),
('Mishkatul', 'Islam', 'mishkat', 'hellomiskat@gmail.com', 1610245263, 'male', 'a'),
('Penelope', 'Haley', 'jikun', 'hoduvy@mailinator.com', 82, 'female', 'Pa$$w0rd!'),
('Celeste', 'Mcclain', 'fyhil', 'latonat@mailinator.com', 72, 'male', 'Pa$$w0rd!'),
('Pankaj', 'Rudra', 'pankaj', 'pankaj@gmail.com', 1121, 'male', 'a'),
('Ruhul', 'Amin', 'ruhul', 'ruhul@gmail.com', 12345678, 'male', 'a'),
('Brent', 'Knapp', 's', 's@s.com', 52, 'male', 's'),
('Shakib', 'Hossain', 'sakib', 'sakib@gmai.com', 1234546, 'male', 'a'),
('Shakib', 'Hossain', 'shakib', 'shakib@gmail.com', 12345678, 'male', 'a'),
('Sobhan', 'S.', 'sobhan', 'sobhan@gmail.com', 541424, 'male', 'a'),
('Suvo', 'S.', 'suvo', 'suvo@gmail.com', 125444, 'male', 'a'),
('Tainur', 't.', 'tainur', 'tainur@gmail.com', 125444, 'male', 'a');
INSERT INTO `flight` (`id`, `source_date`, `source_time`, `dest_date`, `dest_time`, `dep_airport`, `arr_airport`, `seats`, `price`, `flight_class`, `airline_name`, `dep_airport_id`, `arr_airport_id`, `airline_email`) VALUES
(35, '2023-09-22', '04:00:00', '2023-10-07', '18:00:00', 'Shah Amanat International Airport', ' Dhaka Airport', 25, 750.00, 'Economy', 'Biman Bangladesh', 22, 17, 'bimanbangladesh@gmail.com'),
(36, '2023-10-12', '09:45:00', '2023-11-23', '00:00:00', 'Cox’s Bazar Airport', 'Singapore Changi Airport', 45, 3500.00, 'First Class', 'Biman Bangladesh', 18, 29, 'bimanbangladesh@gmail.com'),
(37, '2023-09-20', '06:52:00', '2023-11-30', '03:56:00', 'Jessore Airport', 'King Fahd International Airport', 60, 2900.00, 'Business', 'Qatar Airways', 23, 36, 'Qatarairways@gmail.com'),
(38, '2023-09-22', '13:03:00', '2023-11-25', '07:25:00', 'Shah Amanat International Airport', ' Dhaka Airport', 25, 1000.00, 'Economy', 'Air Asia', 22, 17, 'airasia@gmail.com');
INSERT INTO `payment` (`Name_Card`, `Date_Exp`, `Name_Owned`, `CVC`) VALUES
('1234567890123456', '2025-12-01', 'afifa', '123'),
('9876543210987654', '2026-06-01', 'akib', '456'),
('5432167890123456', '2024-10-01', 'ashab', '789');