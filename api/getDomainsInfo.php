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

      $selectDomains = $conn->query(
        "SELECT * FROM domain"
      );

      $selectDomainsName = $conn_domains->query(
        "SELECT * FROM domain_name"
      );

      $selectDomainsCategories = $conn_domains->query(
        "SELECT * FROM domain_categories"
      );

      $selectDomainsBalance = $conn_domains->query(
        "SELECT * FROM domain_balance"
      );

      $domainsData = $selectDomains->fetchAll(PDO::FETCH_ASSOC);
      $domainsNameData = $selectDomainsName->fetchAll(PDO::FETCH_ASSOC);
      $domainsCategoriesData = $selectDomainsCategories->fetchAll(PDO::FETCH_ASSOC);
      $domainsBalanceData = $selectDomainsBalance->fetchAll(PDO::FETCH_ASSOC);

      $response = [];

      foreach ($domainsData as $domainData) {
        array_push(
          $response,
          [
            'domain_id' => $domainData['id'],
            'name' => getFindDomainInfo($domainData['id'], $domainsNameData)['name'],
            'balance' => getFindDomainInfo($domainData['id'], $domainsBalanceData)['balance'],
            'categories' => getFindDomainInfo($domainData['id'], $domainsCategoriesData)['categories']
          ]
        );
      }

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