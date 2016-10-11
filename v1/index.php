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

      if (count($data) === 0) {
        $arrResponse = array('error' => true, 'message' => 'No more pages exist');
      } else {
        $arrResponse = array('error' => false, 'doctors' => $data);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
    }
    return $response->withJson($arrResponse);
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

      if(count($data) === 0) {
        $arrResponse = array('error' => true, 'message' => 'No more pages exist');
      } else {
        $arrResponse = array('error' => false, 'clinics' => $clinics);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
    }
    return $response->withJson($arrResponse);
  });


  $app->get('/doctors/{id}', function (Request $request, Response $response) use ($app) {
    try {
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');
      $db_connect = $db->connect();

      $doc_id = ((int) $request->getAttribute('id')) > 0 ? ((int) $request->getAttribute('id')) : 0;

      $doc_query = "SELECT * FROM doctors WHERE id = :id;";
      $doc_params = array('id' => $doc_id);

      $doc_prep_query = $db_connect->prepare($doc_query);
      $doc_data = $db->query($doc_prep_query, $doc_params);

      if (count($doc_data) === 0) {
        $arrResponse = array("error" => true, "message" => "No doctor exist with given ID");
      }
      else {
        $arrResponse = array("error" => false,"doctor" => $doc_data[0], "clinics" => null);
      }

      // $prepare_query = "SELECT * FROM clinics LIMIT :limit OFFSET :offset;";
      // $query = $db_connect->prepare($prepare_query);
      // $data = $db->query($query, $params);
    } catch(PDOException $Exception) {
      $data = array('error' => true, 'message' => 'Server is unable to get data');
    }

    return $response->withJson($arrResponse);
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
