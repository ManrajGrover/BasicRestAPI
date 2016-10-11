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

      $doc_id = ((int) $request->getAttribute('id'));

      $doc_query = "SELECT * FROM doctors WHERE id = :id;";
      $doc_params = array('id' => $doc_id);

      $doc_prep_query = $db_connect->prepare($doc_query);
      $doc_data = $db->query($doc_prep_query, $doc_params);

      if (count($doc_data) === 0) {
        $arrResponse = array("error" => true, "message" => "No doctor exist with given ID");
      }
      else {
        $clinics_query = "SELECT clinics.id, clinics.name, clinics.street, clinics.city, clinics.state, clinics.country, clinics.zipcode FROM `clinics` LEFT JOIN doctors_clinics ON clinics.id = doctors_clinics.cl_id WHERE doctors_clinics.doc_id = :id";

        $clinics_prep_query = $db_connect->prepare($clinics_query);
        $clinics_data = $db->query($clinics_prep_query, $doc_params);

        $arrResponse = array("error" => false,"doctor" => $doc_data[0], "clinics" => $clinics_data);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
    }

    return $response->withJson($arrResponse);
  });

  $app->get('/clinics/{id}', function (Request $request, Response $response) use ($app) {
    try {
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');
      $db_connect = $db->connect();

      $cl_id = ((int) $request->getAttribute('id'));

      $cl_query = "SELECT * FROM clinics WHERE id = :id;";
      $cl_params = array('id' => $cl_id);

      $cl_prep_query = $db_connect->prepare($cl_query);
      $cl_data = $db->query($cl_prep_query, $cl_params);

      if (count($cl_data) === 0) {
        $arrResponse = array("error" => true, "message" => "No clinics exist with given ID");
      }
      else {
        $doctors_query = "SELECT doctors.id, doctors.name, doctors.contact, doctors.email FROM `doctors` LEFT JOIN doctors_clinics ON doctors.id = doctors_clinics.doc_id WHERE doctors_clinics.cl_id = :id";

        $doctors_prep_query = $db_connect->prepare($doctors_query);
        $doctors_data = $db->query($doctors_prep_query, $cl_params);

        $arrResponse = array("error" => false,"clinic" => $cl_data[0], "doctors" => $doctors_data);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');
    }
    return $response->withJson($arrResponse);
  });

  $app->run();
?>
