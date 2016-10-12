<?php
  
  /**
   * Returns array with limit and offset based on
   * page number requested
   * @param  Array $params  Associative Array of Get Parameters
   * @return Array          Associative Array of limit and offset
   */
  function getOffset($params) {
    $limit = 10;
    $pageNumber = (int) $params['page'];

    /**
     * If pageNumber is less than 0, set offset to 0
     * else limit * (pageNumber - 1), because 0 index
     */
    if ($pageNumber <= 0) {
      $offset = 0;
    }
    else {
      $offset = $limit * ($pageNumber - 1);
    }

    return array('limit' => $limit, 'offset' => $offset);
  }
?>
