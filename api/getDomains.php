<?php
  require './config.php';
  require './headers.php';

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
      $selectDomains = $conn->query(
        "SELECT * FROM domain"
      );

      $domainsData = $selectDomains->fetchAll(PDO::FETCH_ASSOC);
      
      http_response_code(200);
      echo json_encode($domainsData);
    } catch (Exception $exception) {
      http_response_code(500);
      echo json_encode([
        'message' => 'Непредвиденная ошибка',
        'exception' => $exception
      ]);
    }
  } else {
    http_response_code(400);
    echo json_encode(['message' => 'Неправильный метод запроса']);
  }