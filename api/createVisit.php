<?php
  require './config.php';
  require './headers.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
      $conn->beginTransaction();

      $body = json_decode(file_get_contents('php://input'));

      $selectDomain = $conn->prepare("SELECT id FROM domain WHERE domain=:domain");
      $selectDomain->bindParam(':domain', $body->domain);
      $selectDomain->execute();
      
      $domainId = $selectDomain->fetch(PDO::FETCH_ASSOC)['id'];

      if ($domainId) {
        $prepareVisit = $conn->prepare(
          "INSERT INTO visit (page, domain_id, ip, user_agent, browser, device, platform)
          VALUES (:page, :domain_id, :ip, :user_agent, :browser, :device, :platform)"
        );
        $prepareVisit->bindParam(':page', $body->page);
        $prepareVisit->bindParam(':domain_id', $domainId);
        $prepareVisit->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
        $prepareVisit->bindParam(':user_agent', $body->user_agent);
        $prepareVisit->bindParam(':browser', $body->browser);
        $prepareVisit->bindParam(':device', $body->device);
        $prepareVisit->bindParam(':platform', $body->platform);
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
        'exception' => $exception->getMessage()
      ]);
    }
  } else {
    http_response_code(405);
    echo json_encode(['message' => 'Неправильный метод запроса']);
  }