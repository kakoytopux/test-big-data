<?php
  require './config.php';
  require './headers.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
      $conn->beginTransaction();

      $body = json_decode(file_get_contents('php://input'));
      $prepareUser = $conn->prepare("INSERT INTO user (username) VALUES (:username)");
      $prepareUser->bindParam(':username', $body->username);
      $prepareUser->execute();

      $insertUserId = $conn->lastInsertId();

      $selectVisit = $conn->prepare("SELECT * FROM visit WHERE ip=:ip ORDER BY id DESC LIMIT 1");
      $selectVisit->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
      $selectVisit->execute();

      $visitData = $selectVisit->fetch(PDO::FETCH_ASSOC);

      $prepareDomain = $conn->prepare(
        "INSERT INTO user_domain (user_id, domain_id)
        VALUES ('$insertUserId',  '$visitData[domain_id]')"
      );
      $prepareDomain->execute();

      $prepareContact = $conn->prepare("INSERT INTO contact (visit_id, info) VALUES ('$visitData[id]', :info)");
      $prepareContact->bindParam(':info', $body->info);
      $prepareContact->execute();

      http_response_code(201);
      echo json_encode(['message' => 'Данные сохранены']);
      
      $conn->commit();
    } catch (Exception $exception) {
      $conn->rollBack();

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