CREATE DATABASE IF NOT EXISTS contact_form_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE contact_form_db;

CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS submissions (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(150) NOT NULL,
  email         VARCHAR(150) NOT NULL,
  address       TEXT NOT NULL,
  submitted_by  VARCHAR(50)  NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_submitted_by (submitted_by)
) ENGINE=InnoDB;

INSERT INTO users (username, password_hash) VALUES
('admin', '$2b$10$Br/8zj6QAE1j4mf2YgrAOuYpNpQ0/1lUHFjuySs588sUJGDg0iDvS')
ON DUPLICATE KEY UPDATE username = username;
