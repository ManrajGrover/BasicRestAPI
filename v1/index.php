<?php
  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;

  require_once '../vendor/autoload.php';
  require_once '../includes/DbAccess.php';
  require_once '../includes/functions.php';

  $app = new \Slim\App;

  $app->get('/doctors', function (Request $request, Response $response) use ($app) {
    try {
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');
      $db_connect = $db->connect();

      $allParams = $request->getQueryParams();

      $params = getOffset($allParams);

      $prepare_query = "SELECT * FROM doctors LIMIT :limit OFFSET :offset;";
      $query = $db_connect->prepare($prepare_query);
      $data = $db->query($query, $params);

      $response = $response->withJson($data);
    } catch(PDOException $Exception) {

      $data = array('error' => true, 'message' => 'Server is unable to get data');

    }
    return $response->withJson($data);
  });

  $app->get('/clinics', function (Request $request, Response $response) use ($app) {
    try {
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');
      $db_connect = $db->connect();

      $allParams = $request->getQueryParams();

      $params = getOffset($allParams);
      $prepare_query = "SELECT * FROM clinics LIMIT :limit OFFSET :offset;";
      $query = $db_connect->prepare($prepare_query);
      $data = $db->query($query, $params);

      $response = $response->withJson($data);
    } catch(PDOException $Exception) {

      $data = array('error' => true, 'message' => 'Server is unable to get data');

    }
    return $response->withJson($data);
  });


  $app->get('/doctors/{id}', function (Request $request, Response $response) use ($app) {
    try {
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');
      $db_connect = $db->connect();

      $doc_id = $request->getAttribute('id');

      $params = getOffset($allParams);
      $prepare_query = "SELECT * FROM clinics LIMIT :limit OFFSET :offset;";
      $query = $db_connect->prepare($prepare_query);
      $data = $db->query($query, $params);

      $response = $response->withJson($data);
    } catch(PDOException $Exception) {

      $data = array('error' => true, 'message' => 'Server is unable to get data');

    }
    return $response->withJson($data);
  });

  $app->get('/clinics/{id}', function (Request $request, Response $response) use ($app) {
    try {
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');
      $db_connect = $db->connect();

      $doc_id = $request->getAttribute('id');

      $params = getOffset($allParams);
      $prepare_query = "SELECT * FROM clinics LIMIT :limit OFFSET :offset;";
      $query = $db_connect->prepare($prepare_query);
      $data = $db->query($query, $params);

      $response = $response->withJson($data);
    } catch(PDOException $Exception) {

      $data = array('error' => true, 'message' => 'Server is unable to get data');

    }
    return $response->withJson($data);
  });

  $app->run();
?>
