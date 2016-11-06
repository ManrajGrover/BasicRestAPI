<?php
  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\ResponseInterface as Response;

  /**
   * Load dependencies and includes
   */
  require_once '../vendor/autoload.php';
  require_once '../includes/DbAccess.php';
  require_once '../includes/functions.php';

  /**
   * Slim App instance
   * @var Object
   */
  $app = new \Slim\App;

  /**
   * Matches route to /doctors and gets details of all doctors from the database
   * in paginated format.
   * @option page     Gets the details of doctors on that page. Defaults to 1
   * @return JSON     Details of all doctors on particular page
   */
  $app->get('/doctors', function (Request $request, Response $response) use ($app) {
    try {
      /**
       * Create Database instance
       * @var Object Database Access Class
       */
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');

      /**
       * Connect to Database
       * @var Object
       */
      $db_connect = $db->connect();

      /**
       * Get all query parameters
       */
      $allParams = $request->getQueryParams();

      /**
       * Get offset based on page number
       */
      $params = getOffset($allParams);

      /**
       * Prepare query for getting details of doctors on that page
       * @var String
       */
      $prepare_query = "SELECT * FROM doctors LIMIT :limit OFFSET :offset;";
      $query = $db_connect->prepare($prepare_query);

      /**
       * Query the database and get rows of each doctor
       * @var Array
       */
      $data = $db->query($query, $params);

      /**
       * Check if any doctor exist on the page
       */
      if (count($data) === 0) {
        $arrResponse = array('error' => true, 'message' => 'No more pages exist');

        return $response->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->withJson($arrResponse);
      } else {
        $arrResponse = array('error' => false, 'doctors' => $data);
        return $response->withStatus(200)
                        ->withHeader('Content-Type', 'application/json')
                        ->withJson($arrResponse);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');

      return $response->withStatus(500)
                      ->withHeader('Content-Type', 'application/json')
                      ->withJson($arrResponse);
    }
  });

  /**
   * Matches route to /clinics and gets details of all clinics from the database
   * in paginated format.
   * @option page     Gets the details of clinics on that page. Defaults to 1
   * @return JSON     Details of all clinics on particular page
   */
  $app->get('/clinics', function (Request $request, Response $response) use ($app) {
    try {
      /**
       * Create Database instance
       * @var Object Database Access Class
       */
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');

      /**
       * Connect to Database
       * @var Object
       */
      $db_connect = $db->connect();

      /**
       * Get all query parameters
       */
      $allParams = $request->getQueryParams();

      /**
       * Get offset based on page number
       */
      $params = getOffset($allParams);

      /**
       * Prepare query for getting details of clinics on that page
       * @var String
       */
      $prepare_query = "SELECT * FROM clinics LIMIT :limit OFFSET :offset;";
      $query = $db_connect->prepare($prepare_query);

      /**
       * Query the database and get rows of each clinic
       * @var Array
       */
      $data = $db->query($query, $params);

      /**
       * Check if any doctor exist on the page
       */
      if(count($data) === 0) {
        $arrResponse = array('error' => true, 'message' => 'No more pages exist');

        return $response->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->withJson($arrResponse);
      } else {
        $arrResponse = array('error' => false, 'clinics' => $data);

        return $response->withStatus(200)
                        ->withHeader('Content-Type', 'application/json')
                        ->withJson($arrResponse);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');

      return $response->withStatus(500)
                      ->withHeader('Content-Type', 'application/json')
                      ->withJson($arrResponse);
    }
  });

  /**
   * Matches route to /doctors/:id and gets details of doctor
   * with given id and all clinics where he/she practices
   * from the database
   * @return JSON     Details of doctor and clinics where he practices
   */
  $app->get('/doctors/{id}', function (Request $request, Response $response) use ($app) {
    try {
      /**
       * Create Database instance
       * @var Object Database Access Class
       */
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');

      /**
       * Connect to Database
       * @var Object
       */
      $db_connect = $db->connect();

      /**
       * Get ID attribute from Request
       * @var int
       */
      $doc_id = ((int) $request->getAttribute('id'));

      /**
       * Prepare query for getting details of doctor with given id
       * @var string
       */
      $doc_query = "SELECT * FROM doctors WHERE id = :id;";
      $doc_params = array('id' => $doc_id);
      $doc_prep_query = $db_connect->prepare($doc_query);

      /**
       * Query the database and get all rows for given id
       * @var Array
       */
      $doc_data = $db->query($doc_prep_query, $doc_params);

      /**
       * Check if no doctor exist
       */
      if (count($doc_data) === 0) {
        $arrResponse = array("error" => true, "message" => "No doctor exist with given ID");

        return $response->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->withJson($arrResponse);
      }
      else {
        /**
         * Query to get clinics where doctor works
         * @var string
         */
        $clinics_query = "SELECT clinics.id, clinics.name, clinics.street, clinics.city, clinics.state, clinics.country, clinics.zipcode FROM `clinics` LEFT JOIN doctors_clinics ON clinics.id = doctors_clinics.cl_id WHERE doctors_clinics.doc_id = :id";
        $clinics_prep_query = $db_connect->prepare($clinics_query);

        /**
         * Query the database and get all clinics as array 
         * @var Array
         */
        $clinics_data = $db->query($clinics_prep_query, $doc_params);

        $arrResponse = array("error" => false, "doctor" => $doc_data[0], "clinics" => $clinics_data);

        return $response->withStatus(200)
                        ->withHeader('Content-Type', 'application/json')
                        ->withJson($arrResponse);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');

      return $response->withStatus(500)
                      ->withHeader('Content-Type', 'application/json')
                      ->withJson($arrResponse);
    }
  });

  /**
   * Matches route to /clinics/:id and gets details of clinic
   * with given id and all doctors who practice there
   * from the database
   * @return JSON     Details of clinic and doctors who practice there
   */
  $app->get('/clinics/{id}', function (Request $request, Response $response) use ($app) {
    try {
      /**
       * Create Database instance
       * @var Object Database Access Class
       */
      $db = new DbAccess('localhost', '8889', 'root', 'root', 'healthcare');

      /**
       * Connect to Database
       * @var Object
       */
      $db_connect = $db->connect();

      /**
       * Get ID attribute from Request
       * @var int
       */
      $cl_id = ((int) $request->getAttribute('id'));

      /**
       * Prepare query for getting details of clinic with given id
       * @var string
       */
      $cl_query = "SELECT * FROM clinics WHERE id = :id;";
      $cl_params = array('id' => $cl_id);
      $cl_prep_query = $db_connect->prepare($cl_query);

      /**
       * Query the database and get all rows for given id
       * @var Array
       */
      $cl_data = $db->query($cl_prep_query, $cl_params);

      /**
       * Check if no clinic exist
       */
      if (count($cl_data) === 0) {
        $arrResponse = array("error" => true, "message" => "No clinics exist with given ID");

        return $response->withStatus(404)
                        ->withHeader('Content-Type', 'application/json')
                        ->withJson($arrResponse);
      }
      else {
        /**
         * Query to get doctors who practice at that clinic
         * @var string
         */
        $doctors_query = "SELECT doctors.id, doctors.name, doctors.contact, doctors.email FROM `doctors` LEFT JOIN doctors_clinics ON doctors.id = doctors_clinics.doc_id WHERE doctors_clinics.cl_id = :id";
        $doctors_prep_query = $db_connect->prepare($doctors_query);

        /**
         * Query the database and get all doctors as array 
         * @var Array
         */
        $doctors_data = $db->query($doctors_prep_query, $cl_params);

        $arrResponse = array("error" => false, "clinic" => $cl_data[0], "doctors" => $doctors_data);

        return $response->withStatus(200)
                        ->withHeader('Content-Type', 'application/json')
                        ->withJson($arrResponse);
      }

    } catch(PDOException $Exception) {
      $arrResponse = array('error' => true, 'message' => 'Server is unable to get data');

      return $response->withStatus(500)
                      ->withHeader('Content-Type', 'application/json')
                      ->withJson($arrResponse);
    }

  });

  $app->run();
?>
