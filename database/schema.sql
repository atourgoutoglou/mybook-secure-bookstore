-- MyBook — database schema for local development (MySQL / MariaDB)
-- Import with phpMyAdmin (Import tab) or:  mysql -u root < database/schema.sql

CREATE DATABASE IF NOT EXISTS bookstore_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bookstore_db;

-- Users of all roles. Employees are pre-registered by an admin (email + security code)
-- and complete their account later, so username/password may be NULL until then.
CREATE TABLE IF NOT EXISTS users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  username    VARCHAR(100) NULL,
  password    VARCHAR(255) NULL,            -- bcrypt hash (password_hash)
  email       VARCHAR(255) NOT NULL UNIQUE,
  phonenumber VARCHAR(20)  NULL,
  number_id   VARCHAR(10)  NULL,            -- 5-digit security code for employees / admins
  role        ENUM('customer','employee','admin') NOT NULL DEFAULT 'customer',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Security codes issued by an admin to authorise employee registration
CREATE TABLE IF NOT EXISTS securitycodes (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  number_id VARCHAR(10)  NOT NULL,
  email     VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS books (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255)  NOT NULL,
  image_path  VARCHAR(255)  NOT NULL,       -- e.g. Images/book1.png
  author      VARCHAR(255)  NOT NULL,
  publisher   VARCHAR(255)  NOT NULL,
  pages       INT           NOT NULL,
  price       DECIMAL(8,2)  NOT NULL,
  stars       DECIMAL(2,1)  NOT NULL,
  description TEXT          NOT NULL
);

-- Demo admin account (local development only — change it!)
--   e-mail: admin@mybook.local   password: Admin123!   security code: 12345
INSERT INTO users (username, password, email, phonenumber, number_id, role)
VALUES ('admin', '$2y$10$JsX7rFpfKBf3qj/4Szyp/e3DQS6m69T5MutwL2z5tzV0z/oJa9TsK', 'admin@mybook.local', '6900000000', '12345', 'admin')
ON DUPLICATE KEY UPDATE email = email;
