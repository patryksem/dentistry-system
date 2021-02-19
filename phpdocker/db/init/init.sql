
-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `attachemnt`
--

CREATE TABLE `attachemnt` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `monday` tinyint(1) NOT NULL,
  `tuesday` tinyint(1) NOT NULL,
  `wednesday` tinyint(1) NOT NULL,
  `thursday` tinyint(1) NOT NULL,
  `friday` tinyint(1) NOT NULL,
  `saturday` tinyint(1) NOT NULL,
  `sunday` tinyint(1) NOT NULL,
  `work_hours` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `department`
--

INSERT INTO `department` (`id`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `work_hours`, `name`, `active`) VALUES
(2, 1, 1, 1, 1, 0, 0, 0, '07-15', 'swiateczny', 0),
(5, 0, 1, 1, 1, 1, 1, 0, '07-15', 'Wiosna 2021', 0),
(7, 1, 1, 0, 1, 1, 1, 1, '09-22', 'Wiosna 2021', 0),
(8, 0, 1, 1, 1, 1, 1, 0, '09-18', 'Zima 2021', 0),
(10, 1, 1, 1, 1, 1, 0, 0, '08-22', 'Rok 2021', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `residence_place` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `name`, `lastname`, `birth_date`, `residence_place`, `phone`, `email`, `roles`, `password`, `is_verified`) VALUES
(16, 'Patryk', 'Semla', '1997-04-19', 'Łąkowa 5 Bielsko-Biała', '345321234', 'widmoowy@gmail.com', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$ZvbhWIUHg+M/geFzjUGTQg$62UqcmQEKblD3rDOL1Qfd5HdTWUtbrNmcmpem5qLpbQ', 0),
(17, 'Patryk', 'Semla', '1979-12-05', 'Sasanek 8 Kęty', '988907655', 'seml1apatryk@interia.pl', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$0O9ZyyEpLkID4yNjN1VWrg$8/cRZRj1f2n8QkoAylXYlLIGuIsQgq8uQ11v9tXbWGs', 0),
(18, 'Aleksandra', 'Nowak', '1985-06-05', 'Krakowska 67 Oświęcim', '765567879', 'semlapatryk@interia.pl', '[\"ROLE_DOCTOR\"]', '$argon2id$v=19$m=65536,t=4,p=1$+MZpqSq1IDA6VkAW+QU3rw$3hpvhhuRtjPe7N4uQDg4Cuk79VTT8pDgyYrtEzJKVbY', 0),
(19, 'Jan', 'Kowalski', '2000-05-07', 'Srebrna 3 Kozy', '567876456', 'jankowalski@test.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$dgFT5Mj552Bg+Mtw7PsXlw$gcxLleIBqUh82gLSdX6gd1eotaDsiUgsrdBKRJnbDG8', 0),
(20, 'Sandra', 'Kowalska', '2001-03-09', 'Złota 3 Żywiec', '746372384', 'sandrakowalska@test.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$pc88ob0UPT05l3wynRXIVw$Gk8xJ/ivI2sCopnl3kBuSF5Aut1a6ImFA+V2PXFhuyk', 0),
(28, 'Marcin', 'Kowalski', '2001-06-13', 'Srebrna 3 Kozy', '567876567', 'marcinkowalski1@onet.pl', '[\"ROLE_USER\"]', '$argon2id$v=19$m=65536,t=4,p=1$WvCZM+BZ45Z6/DxiexGmow$+357I30mEr6Y+tBR2X5xp35djow3TQTVHrwbY44JYL0', 0),
(29, 'Dariusz', 'Zontek', '1984-03-04', 'Sasanek 8 Kęty', '768987678', 'dariuszzontek1@gmail.com', '[\"ROLE_DOCTOR\"]', '$argon2id$v=19$m=65536,t=4,p=1$Hv1eABymLG1jYfbgwsiD1w$rDfwMzNgU8LTpc7LW30FKpUK4fL5LbYnw8u6pMZHwbA', 0),
(30, 'Adam', 'Nowak', '1980-03-02', 'Katowice ul. Wolności 3', '786778678', 'ksandra@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$G0+24gdd60VcB4TqB1MSbw$uIPICrOVDCbmykf6ioZd0hFFMbcRvfhSZ0C7yj61DE4', 0),
(46, 'Dariusz', 'Wydra', '1975-05-04', 'ul.Srebrna3 Kraków', '345542878', 'dariuszwydra54@gmail.com', '[]', '$argon2id$v=19$m=65536,t=4,p=1$Hb541gQSNQQtp/kpjm2G/g$DWdsa/iVhxJTNbOKrD4n0gaaKYvhcI76I0U68YVR0+4', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `visit`
--

CREATE TABLE `visit` (
  `id` int(11) NOT NULL,
  `patient_id_id` int(11) NOT NULL,
  `doctor_id_id` int(11) NOT NULL,
  `visit_date` datetime NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Zrzut danych tabeli `visit`
--

INSERT INTO `visit` (`id`, `patient_id_id`, `doctor_id_id`, `visit_date`, `description`, `comment`, `status`) VALUES
(21, 19, 17, '2021-01-07 08:00:00', '', 'Wstawienie plomby w dolnej prawej jedynce.', 1),
(24, 19, 18, '2021-01-05 12:00:00', '', 'Wybielanie zębów.', 1),
(29, 28, 17, '2021-01-28 12:00:00', '', 'Wybielanie zębów', 1),
(30, 19, 17, '2021-01-27 12:00:00', '', 'Wstawiono plombe', 1),
(36, 28, 17, '2021-01-21 11:30:00', 'gfhdfgdhfgd', 'gfhfggf', 1),
(44, 17, 29, '2021-02-01 17:30:00', '', '', 1),
(45, 30, 18, '2021-02-08 08:00:00', '', NULL, 0),
(46, 30, 18, '2021-02-08 09:30:00', '', NULL, 1),
(47, 20, 18, '2021-02-08 11:00:00', '', NULL, 0),
(48, 20, 18, '2021-02-02 12:30:00', '', NULL, 0),
(49, 19, 18, '2021-02-08 10:00:00', 'Ukruszona jedynka na dole.', 'Odbudowano ząb numer jeden na dole, proszę o niespożywanie twardych pokarmów przez jeden dzień i umówienie się na wizytę kontrolną po miesiącu.', 1),
(50, 28, 18, '2021-02-05 20:00:00', '', NULL, 0),
(51, 19, 18, '2021-02-08 15:00:00', '', NULL, 0),
(59, 46, 18, '2021-02-15 16:30:00', 'Uszczerbiona jedynka.', 'Odbudowano prawą dolną jedynkę.', 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `attachemnt`
--
ALTER TABLE `attachemnt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B2F8A0ECA76ED395` (`user_id`);

--
-- Indeksy dla tabeli `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Indeksy dla tabeli `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_437EE939EA724598` (`patient_id_id`),
  ADD KEY `IDX_437EE93932B07E31` (`doctor_id_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `attachemnt`
--
ALTER TABLE `attachemnt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT dla tabeli `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT dla tabeli `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `attachemnt`
--
ALTER TABLE `attachemnt`
  ADD CONSTRAINT `FK_B2F8A0ECA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Ograniczenia dla tabeli `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `FK_437EE93932B07E31` FOREIGN KEY (`doctor_id_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_437EE939EA724598` FOREIGN KEY (`patient_id_id`) REFERENCES `user` (`id`);
COMMIT;