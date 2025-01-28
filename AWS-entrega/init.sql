CREATE TABLE IF NOT EXISTS `apartment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `occupied` tinyint(1) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `apartment`;
INSERT INTO `apartment` (`id`, `description`, `occupied`, `title`, `direction`, `price`) VALUES
	(1, 'Piso 4 Habitaciones y 2 Baños en una zona muy bien comunicada', 0, 'Piso Sancho el Fuerte', 'Sancho el Fuerte 27 5C', 980),
	(2, 'Apartamento 1 Hab recien reformado', 0, 'Piso Casco Viejo', 'San Nicolas 21 3ºDR', 550);

CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `api_key` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `client`;
INSERT INTO `client` (`id`, `api_key`) VALUES
	(1, '1234');

CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

DELETE FROM `doctrine_migration_versions`;

CREATE TABLE IF NOT EXISTS `photo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apartment_id` int NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_14B78418176DFE85` (`apartment_id`),
  CONSTRAINT `FK_14B78418176DFE85` FOREIGN KEY (`apartment_id`) REFERENCES `apartment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `photo`;
INSERT INTO `photo` (`id`, `apartment_id`, `url`) VALUES
	(1, 1, 'https://image-proxy.libere.app/images/jpg:1920/plain/https://air-production-asset-images.storage.googleapis.com/42490f16-14ee-43f3-9b11-90f1c2f3b2f4/space-categories/f894bb3e-cbd8-48ac-9164-aed6007ca89a/a80ad239-6fdb-4c08-9aac-af0a008d306c.jpg@jpg'),
	(2, 2, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/454797909.jpg?k=15c5b906b236f4ca73879afdd35ff772afd755632dc476b0da09977b323496f2&o=&hp=1'),
	(3, 2, 'https://www.viacelere.com/wp-content/uploads/old-blog/2022/11/salon02-scaled.jpg');

CREATE TABLE IF NOT EXISTS `reservation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apartment_id` int NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `cancelled` tinyint(1) NOT NULL,
  `cancellation_date` datetime DEFAULT NULL,
  `reservation_contact` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_42C84955176DFE85` (`apartment_id`),
  CONSTRAINT `FK_42C84955176DFE85` FOREIGN KEY (`apartment_id`) REFERENCES `apartment` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `reservation`;
INSERT INTO `reservation` (`id`, `apartment_id`, `start_date`, `end_date`, `cancelled`, `cancellation_date`, `reservation_contact`) VALUES
	(1, 1, '2025-01-23 11:04:55', '2026-01-24 11:04:57', 0, NULL, 'juan.perez@gmail.com');