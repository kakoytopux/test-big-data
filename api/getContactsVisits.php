<?php
  require './config.php';
  require './headers.php';

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
      $selectContacts = $conn->query("SELECT * FROM contact");
      $contactsData = $selectContacts->fetchAll(PDO::FETCH_ASSOC);

      $response = [];

      // foreach ($contactsData as $contactData) {
        
      // }
      
      http_response_code(200);
      echo json_encode($response);
    } catch (Exception $exception) {
      http_response_code(500);
      echo json_encode([
        'message' => 'Непредвиденная ошибка',
        'exception' => $exception->getMessage()
      ]);
    }
  } else {
    http_response_code(405);
    echo json_encode(['message' => 'Неправильный метод запроса']);
  }