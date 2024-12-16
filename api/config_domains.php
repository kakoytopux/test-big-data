<?php
  try {
    $conn_domains = new PDO('mysql:host=localhost:3306; dbname=test-big-data-two', 'root', 'root');

    $conn_domains->query(
      "CREATE TABLE IF NOT EXISTS domain_name (
        id INT PRIMARY KEY AUTO_INCREMENT,
        domain_id INT NOT NULL,
        name VARCHAR(50) NOT NULL
      )"
    );

    $conn_domains->query(
      "CREATE TABLE IF NOT EXISTS domain_categories (
        id INT PRIMARY KEY AUTO_INCREMENT,
        domain_id INT NOT NULL,
        categories VARCHAR(50) NOT NULL
      )"
    );

    $conn_domains->query(
      "CREATE TABLE IF NOT EXISTS domain_balance (
        id INT PRIMARY KEY AUTO_INCREMENT,
        domain_id INT NOT NULL,
        balance INT NOT NULL
      )"
    );

    $conn_domains->query(
      "INSERT IGNORE INTO domain_name (id, domain_id, name) VALUES (1, 1, 'Локальный')"
    );

    $conn_domains->query(
      "INSERT IGNORE INTO domain_categories (id, domain_id, categories) VALUES (1, 1, 'Локальная штуковина')"
    );

    $conn_domains->query(
      "INSERT IGNORE INTO domain_balance (id, domain_id, balance) VALUES (1, 1, 1000)"
    );
  } catch (PDOException $exception) {
    echo 'Соединение с базой данных не удалось: ' . $exception->getMessage();
  }