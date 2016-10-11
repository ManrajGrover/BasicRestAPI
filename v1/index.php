<?php
  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;

  require_once '../vendor/autoload.php';
  require_once '../includes/DbAccess.php';

  $app = new \Slim\App;

  $app->get('/doctors', function (Request $request, Response $response) use ($app) {
    try {
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');
      $db_connect = $db->connect();

      $allParams = $request->getQueryParams();
      $limit = 10;

      $pageNumber = (int)$allParams['page'];
      if ($pageNumber <= 0) {
        $offset = 0;
      }
      else {
        $offset = $limit * ($pageNumber - 1);
      }

      $params = array('limit' => $limit, 'offset' => $offset);
      $prepare_query = "SELECT * FROM doctors LIMIT :limit OFFSET :offset;";
      $query = $db_connect->prepare($prepare_query);
      $data = $db->query($query, $params);

      $response = $response->withJson($data);
    } catch(PDOException $Exception) {

      $data = array('error' => true, 'message' => 'Server is unable to get data');

    }
    return $response->withJson($data);
  });

  $app->get('/clinics', 'getAllClinics');
  $app->get('/doctors/{id}', 'getDoctorDetails');
  $app->get('/clinics/{id}', 'getClinicsDetails');


  function getAllClinics(Request $request, Response $response) {

  }

  function getDoctorDetails (Request $request, Response $response) {
    $doc_id = $request->getAttribute('id');
  }

  function getClinicsDetails (Request $request, Response $response) {
    $clinic_id = $request->getAttribute('id');
  }

  $app->run();
?>
