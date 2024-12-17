<?php
  require './config.php';
  require './config_domains.php';
  require './headers.php';

  function getFindDomainInfo($domainId, $infoArr): mixed {
    $infoObj = [];

    foreach ($infoArr as $info) {
      if ($info['domain_id'] === $domainId) {
        $infoObj = $info;
      }
    }

    return $infoObj;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
      $conn->beginTransaction();
      $conn_domains->beginTransaction();

      $domainId = $_GET['id'];

      $selectDomains = $conn->prepare("SELECT * FROM domain WHERE id=:id");
      $selectDomains->bindParam(':id', $domainId, PDO::PARAM_INT);
      $selectDomains->execute();

      $selectDomainsName = $conn_domains->prepare("SELECT * FROM domain_name WHERE domain_id=:id");
      $selectDomainsName->bindParam(':id', $domainId, PDO::PARAM_INT);
      $selectDomainsName->execute();

      $selectDomainsBalance = $conn_domains->prepare("SELECT * FROM domain_balance WHERE domain_id=:id");
      $selectDomainsBalance->bindParam(':id', $domainId, PDO::PARAM_INT);
      $selectDomainsBalance->execute();

      $selectDomainsCategories = $conn_domains->prepare("SELECT * FROM domain_categories WHERE domain_id=:id");
      $selectDomainsCategories->bindParam(':id', $domainId, PDO::PARAM_INT);
      $selectDomainsCategories->execute();

      $domainsData = $selectDomains->fetch(PDO::FETCH_ASSOC);
      $domainsNameData = $selectDomainsName->fetch(PDO::FETCH_ASSOC);
      $domainsBalanceData = $selectDomainsBalance->fetch(PDO::FETCH_ASSOC);
      $domainsCategoriesData = $selectDomainsCategories->fetchAll(PDO::FETCH_ASSOC);

      $response = [
        'domain_id' => $domainsData['id'],
        'name' => $domainsNameData['name'],
        'balance' => $domainsBalanceData['balance'],
        'categories' => $domainsCategoriesData
      ];

      http_response_code(200);
      echo json_encode($response);

      $conn->commit();
      $conn_domains->commit();
    } catch (Exception $exception) {
      $conn->rollBack();
      $conn_domains->rollBack();

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