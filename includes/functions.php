<?php
  function getOffset($params) {
    $limit = 10;
    $pageNumber = (int) $params['page'];

    if ($pageNumber <= 0) {
      $offset = 0;
    }
    else {
      $offset = $limit * ($pageNumber - 1);
    }

    return array('limit' => $limit, 'offset' => $offset);
  }
?>
