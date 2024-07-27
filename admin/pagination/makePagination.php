<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * @file Pagination
 */

class makePagination
{

  public function __construct($database)
  {
    $this->database = $database;
  }

  public function getTotalOfRows()
  {
    global $wpdb;
    $getTotalOfRows = $wpdb->get_var("SELECT COUNT(*) FROM " . $this->database . " ");

    return $getTotalOfRows;
  }

  public function returnListPaginated($offset, $stopValue)
  {
    global $wpdb;
    $table = $wpdb->prefix . 'chocoletras_plugin';

    // Modify the query to order by ID in descending order
    $query = $wpdb->prepare("SELECT * FROM $table ORDER BY id DESC LIMIT %d OFFSET %d", $stopValue, $offset);

    $results = $wpdb->get_results($query);
    return $results;
  }



  public function paginationElements()
  {
    return self::getTotalOfRows();
  }

}