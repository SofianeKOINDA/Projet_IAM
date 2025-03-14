-- Increase password column size to accommodate bcrypt hashes
ALTER TABLE `clients` MODIFY `mot_de_passe` VARCHAR(255) NOT NULL;
ALTER TABLE `administrateurs` MODIFY `mot_de_passe` VARCHAR(255) NOT NULL;

-- Update existing admin password to use bcrypt hash
UPDATE `administrateurs` SET `mot_de_passe` = '$2y$10$V7z8b9Z1u2O3e4r5t6y7u8i9o0p1a2b3c4d5e6f7g8h9i0j1k2l3m4n5o6p' WHERE `id_admin` = 1;
