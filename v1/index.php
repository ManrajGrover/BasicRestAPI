<?php
  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;

  require_once '../vendor/autoload.php';
  require_once '../includes/DbAccess.php';

  $app = new \Slim\App;

  $app->get('/doctors', 'getAllDoctors');
  $app->get('/clinics', 'getAllClinics');
  $app->get('/doctors/{id}', 'getDoctorDetails');
  $app->get('/clinics/{id}', 'getClinicsDetails');

  function getAllDoctors (Request $request, Response $response) {
    $db_connect = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');
    $data = array('name' => 'Bob', 'age' => 40);
    $response = $response->withJson($data);
    return $response;
  }

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
