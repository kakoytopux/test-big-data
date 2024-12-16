<?php
  require './config.php';
  require './headers.php';

  function getCountVisitPrevMonth($visitsData): int {
    $count = 0;

    foreach ($visitsData as $visitData) {
      if (date('Y-m', strtotime($visitData['created_at'])) === date('Y-m', strtotime('-1 month'))) {
        $count++;
      }
    }

    return $count;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
      $conn->beginTransaction();

      $selectDomains = $conn->query(
        "SELECT * FROM domain WHERE created_at >= NOW() - INTERVAL 7 DAY"
      );

      $selectVisits = $conn->query(
        "SELECT * FROM visit"
      );

      $selectContacts = $conn->query(
        "SELECT * FROM contact"
      );

      $domainsData = $selectDomains->fetchAll(PDO::FETCH_ASSOC);
      $visitsData = $selectVisits->fetchAll(PDO::FETCH_ASSOC);
      $contactsData = $selectContacts->fetchAll(PDO::FETCH_ASSOC);
      
      $response = [];

      foreach ($domainsData as $domainData) {
        $visitsFiltered = array_filter($visitsData, fn($visit) => $visit['domain_id'] === $domainData['id']);
        $contactsFiltered = [];
        $contactsVisitsIds = [];

        foreach ($visitsFiltered as $visitFiltered) {
          foreach ($contactsData as $contactData) {
            if ($visitFiltered['id'] === $contactData['visit_id']) {
              array_push($contactsFiltered, $contactData);
              array_push($contactsVisitsIds, $contactData['visit_id']);
            }
          }
        }

        array_push(
          $response,
          [
            'domain_id' => $domainData['id'],
            'domain' => $domainData['domain'],
            'count_visits' => count($visitsFiltered),
            'count_visits_prev_month' => getCountVisitPrevMonth($visitsFiltered),
            'count_contacts' => count($contactsFiltered),
            'count_users_contacts' => count(array_unique($contactsVisitsIds)),
            'date_last_contact' => $contactsFiltered[count($contactsFiltered) - 1]['created_at']
          ]
        );
      }

      http_response_code(200);
      echo json_encode($response);
      
      $conn->commit();
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