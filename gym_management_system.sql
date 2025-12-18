
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `access_card` (
  `Access_card_id` int(11) NOT NULL,
  `Member_id` int(11) DEFAULT NULL,
  `Status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `attendance` (
  `Attendance_id` int(11) NOT NULL,
  `Member_id` int(11) DEFAULT NULL,
  `Access_card_id` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Check_in_time` time DEFAULT NULL,
  `Check_out_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `members` (
  `Member_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Age` int(11) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Membership_id` int(11) DEFAULT NULL,
  `Access_card_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `membership` (
  `Membership_id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Duration` int(11) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `payments` (
  `Payment_id` int(11) NOT NULL,
  `Member_id` int(11) DEFAULT NULL,
  `Amount` decimal(10,2) DEFAULT NULL,
  `Payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `trainers` (
  `Trainer_id` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Specialization` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `trainer_assignment` (
  `Assignment_id` int(11) NOT NULL,
  `Member_id` int(11) DEFAULT NULL,
  `Trainer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE `access_card`
  ADD PRIMARY KEY (`Access_card_id`);


ALTER TABLE `attendance`
  ADD PRIMARY KEY (`Attendance_id`),
  ADD KEY `Member_id` (`Member_id`),
  ADD KEY `Access_card_id` (`Access_card_id`);


ALTER TABLE `members`
  ADD PRIMARY KEY (`Member_id`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `Membership_id` (`Membership_id`),
  ADD KEY `Access_card_id` (`Access_card_id`);

ALTER TABLE `membership`
  ADD PRIMARY KEY (`Membership_id`);


ALTER TABLE `payments`
  ADD PRIMARY KEY (`Payment_id`),
  ADD KEY `Member_id` (`Member_id`);


ALTER TABLE `trainers`
  ADD PRIMARY KEY (`Trainer_id`),
  ADD UNIQUE KEY `Email` (`Email`);


ALTER TABLE `trainer_assignment`
  ADD PRIMARY KEY (`Assignment_id`),
  ADD KEY `Member_id` (`Member_id`),
  ADD KEY `Trainer_id` (`Trainer_id`);


ALTER TABLE `access_card`
  MODIFY `Access_card_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `attendance`
  MODIFY `Attendance_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `members`
  MODIFY `Member_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `membership`
  MODIFY `Membership_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `payments`
  MODIFY `Payment_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `trainers`
  MODIFY `Trainer_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `trainer_assignment`
  MODIFY `Assignment_id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`Member_id`) REFERENCES `members` (`Member_id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`Access_card_id`) REFERENCES `access_card` (`Access_card_id`);


ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`Membership_id`) REFERENCES `membership` (`Membership_id`),
  ADD CONSTRAINT `members_ibfk_2` FOREIGN KEY (`Access_card_id`) REFERENCES `access_card` (`Access_card_id`);


ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`Member_id`) REFERENCES `members` (`Member_id`);


ALTER TABLE `trainer_assignment`
  ADD CONSTRAINT `trainer_assignment_ibfk_1` FOREIGN KEY (`Member_id`) REFERENCES `members` (`Member_id`),
  ADD CONSTRAINT `trainer_assignment_ibfk_2` FOREIGN KEY (`Trainer_id`) REFERENCES `trainers` (`Trainer_id`);
COMMIT;

