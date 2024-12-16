<?php
  try {
    $conn = new PDO('mysql:host=localhost:3306; dbname=test-big-data', 'root', 'root');

    $conn->query(
      "CREATE TABLE IF NOT EXISTS user (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(30) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT NOW()
      )"
    );

    $conn->query(
      "CREATE TABLE IF NOT EXISTS domain (
        id INT PRIMARY KEY AUTO_INCREMENT,
        domain VARCHAR(255) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT NOW()
      )"
    );
  
    $conn->query(
      "CREATE TABLE IF NOT EXISTS visit (
        id INT PRIMARY KEY AUTO_INCREMENT,
        page VARCHAR(255) NOT NULL,
        domain_id INT NOT NULL,
        ip VARCHAR(50) NOT NULL,
        user_agent TEXT NOT NULL,
        browser VARCHAR(30) NOT NULL,
        device VARCHAR(20) NOT NULL,
        platform VARCHAR(20) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        FOREIGN KEY (domain_id) REFERENCES domain(id)
      )"
    );
  
    $conn->query(
      "CREATE TABLE IF NOT EXISTS contact (
        id INT PRIMARY KEY AUTO_INCREMENT,
        visit_id INT NOT NULL,
        info VARCHAR(255) NOT NULL,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        FOREIGN KEY (visit_id) REFERENCES visit(id)
      )"
    );

    $conn->query(
      "CREATE TABLE IF NOT EXISTS user_domain (
        id INT PRIMARY KEY AUTO_INCREMENT,
        domain_id INT NOT NULL,
        user_id INT NOT NULL,
        created_at DATETIME NOT NULL DEFAULT NOW(),
        FOREIGN KEY (user_id) REFERENCES user(id),
        FOREIGN KEY (domain_id) REFERENCES domain(id)
      )"
    );

    $conn->query(
      "INSERT IGNORE INTO domain (id, domain) VALUES (1, 'localhost')"
    );
  } catch (PDOException $exception) {
    echo 'Соединение с базой данных не удалось: ' . $exception->getMessage();
  }