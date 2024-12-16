<?php
  require './config.php';
  require './headers.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
      $conn->beginTransaction();

      $body = json_decode(file_get_contents('php://input'));

      $selectDomain = $conn->query(
        "SELECT id FROM domain WHERE domain='$body->domain'"
      );
      
      $domainId = $selectDomain->fetch(PDO::FETCH_ASSOC)['id'];

      if ($domainId) {
        $prepareVisit = $conn->prepare(
          "INSERT INTO visit (page, domain_id, ip, user_agent, browser, device, platform)
          VALUES ('$body->page', '$domainId', '$_SERVER[REMOTE_ADDR]', '$body->user_agent', '$body->browser', '$body->device', '$body->platform')"
        );

        $prepareVisit->execute();

        http_response_code(201);
        echo json_encode(['message' => 'Данные сохранены']);
        
        $conn->commit();
      } else {
        http_response_code(404);
        echo json_encode(['message' => 'Домен не найден']);
      }
    } catch (Exception $exception) {
      $conn->rollBack();

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