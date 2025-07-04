-- UPDATED ALWAYS ON TOP

-- Jeff 07-04-2025
ALTER TABLE `documents` 
  CHANGE `created_at` `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `documents` 
  CHANGE `id` `id` INT(11) NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

-- Jeff  06-30-2025
CREATE TABLE `cbt`.`documents` 
  (`id` INT(11) NULL , 
  `accounts_id` INT(11) UNSIGNED NOT NULL , 
  `file_name` VARCHAR(150) NOT NULL , 
  `created_at` DATETIME NOT NULL ) ENGINE = InnoDB;

ALTER TABLE `accounts_access` ADD INDEX(`role_id`);

ALTER TABLE `accounts_access` ADD INDEX(`updated_by`);

ALTER TABLE `accounts_access` 
  CHANGE `type` `role_id` TINYINT UNSIGNED NOT NULL;

-- Jeff 06-29-2025
ALTER TABLE `accounts_info` 
  ADD `contact` VARCHAR(11) NOT NULL DEFAULT '0' AFTER `gender`;

ALTER TABLE `ministries` 
  ADD `auto` TINYINT NOT NULL DEFAULT '0' AFTER `age_end`;

-- Jeff 06-28-2025
CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`accounts_id`) USING BTREE;

-- Jeff 06-23-2025
CREATE TABLE `accounts` (
  `id` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `status` enum('active','inactive','banned') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `accounts` (`id`, `email`, `pass`, `created_at`, `updated_at`, `status`) VALUES
(9, 'q.q.q@example.com', '$2y$10$R7iKrQ4R1.A4/2eywfS.vuRMztHqirm2vnJNNtgJPM0jW/2zhit.a', '2025-06-07 10:56:18', '2025-06-07 10:56:18', 'active'),
(10, 'jefferson.amaba.jacobo@example.com', '$2y$10$YLj1oBTjSFKyhQsICpf5RuaFYDKkWsdFcbs5lecZ0PkTW6cWUeIPS', '2025-06-13 19:03:32', '2025-06-13 19:03:32', 'active'),
(11, 'q.q.q@example.com', '$2y$10$lsODMN/hUoY1pJwp2/ayNevcrhQbbvzXsaIgXoh.FqzPVeZOo.SIi', '2025-06-18 17:36:44', '2025-06-18 17:36:44', 'active');

CREATE TABLE `accounts_access` (
  `accounts_id` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `updated_by` int(100) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `accounts_address` (
  `accounts_id` int(255) NOT NULL,
  `address_line` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal` int(11) NOT NULL,
  `is_primary` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `accounts_address` (`accounts_id`, `address_line`, `city`, `state`, `postal`, `is_primary`) VALUES
(9, 'q', 'q', 'q', 1100, 0),
(10, 'q', 'q', 'q', 1100, 0),
(11, 'q', 'q', 'q', 1100, 0);

CREATE TABLE `accounts_info` (
  `accounts_id` int(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `bday` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `baptist_date` datetime NOT NULL,
  `inviter_id` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `accounts_info` (`accounts_id`, `first_name`, `middle_name`, `last_name`, `bday`, `gender`, `baptist_date`, `inviter_id`, `updated_at`) VALUES
(9, 'q', 'q', 'q', '2025-06-07', 'male', '2025-06-07 10:56:18', 0, '2025-06-07 10:56:18'),
(10, 'Jefferson', 'Amaba', 'Jacobo', '2025-06-13', 'male', '2025-06-13 19:03:32', 0, '2025-06-13 19:03:32'),
(11, 'q', 'q', 'q', '2025-06-18', 'male', '2025-06-18 17:36:44', 0, '2025-06-18 17:36:44');

CREATE TABLE `commitments` (
  `id` int(11) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `amount` int(255) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `commitments` (`id`, `accounts_id`, `type_id`, `amount`, `start_date`, `end_date`) VALUES
(1, 10, 2, 1500, '2025-06-14 08:00:00', '2025-06-15 08:00:00'),
(2, 9, 1, 5000, '2025-06-14 08:00:00', '2025-06-20 08:00:00');

CREATE TABLE `commitments_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `commitments_type` (`id`, `name`, `added_date`) VALUES
(1, 'test', '2025-06-14 19:22:31'),
(2, 'test2', '2025-06-14 19:22:31');

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_location` varchar(100) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `ministries` varchar(100) NOT NULL DEFAULT 'all'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `events` (`id`, `event_name`, `event_location`, `start_date`, `end_date`, `ministries`) VALUES
(14, 'Young People Fellowship', 'Poracay, Pampanga', '2025-06-05 08:00:00', '2025-06-06 12:00:00', '2,4'),
(15, 'Mens Fellowship', 'Cabangan, Zambales', '2025-06-06 05:00:00', '2025-06-07 13:00:00', '1'),
(16, 'Test All', 'CBT Olongapo', '2025-06-20 06:00:00', '2025-06-20 07:00:00', 'all');

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `accounts_id` int(11) NOT NULL,
  `text` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `logs` (`id`, `accounts_id`, `text`, `date`) VALUES
(1, 1, 'Created account for Juan Dela Cruz', '2025-06-10 08:15:00'),
(2, 2, 'Added commitment: First Fruit offering of 1500 pesos', '2025-06-11 09:30:00'),
(3, 3, 'Joined the \"Youth Fellowship\" event at Main Hall on 2025-06-15 14:00', '2025-06-11 10:00:00'),
(4, 1, 'Attended Sunday Service at Central Church on 2025-06-09 09:00', '2025-06-09 11:00:00'),
(5, 4, 'Gave tithe offering of 2000 pesos', '2025-06-12 08:45:00'),
(6, 2, 'Created account for Maria Santos', '2025-06-08 16:00:00'),
(7, 3, 'Added commitment: Pledge for church renovation - 5000 pesos', '2025-06-13 13:20:00'),
(8, 5, 'Registered for \"Men of Faith\" retreat at Tagaytay, 2025-07-01 08:00', '2025-06-14 07:50:00'),
(9, 1, 'Created event: Prayer Meeting at Room 5 on 2025-06-20 18:30', '2025-06-15 15:10:00'),
(10, 2, 'Added commitment: Monthly support - 1000 pesos/month', '2025-06-16 12:00:00');

CREATE TABLE `ministries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age_start` tinyint(4) NOT NULL,
  `age_end` tinyint(4) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ministries` (`id`, `name`, `age_start`, `age_end`, `active`) VALUES
(1, 'Mens', 25, 99, 1),
(2, 'Young Pro', 20, 50, 1);

ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `accounts_access`
  ADD PRIMARY KEY (`accounts_id`);

ALTER TABLE `accounts_address`
  ADD KEY `accounts_id` (`accounts_id`);

ALTER TABLE `accounts_info`
  ADD PRIMARY KEY (`accounts_id`);

ALTER TABLE `commitments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `commitments_type`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ministries`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `accounts`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

ALTER TABLE `commitments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `commitments_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `ministries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;