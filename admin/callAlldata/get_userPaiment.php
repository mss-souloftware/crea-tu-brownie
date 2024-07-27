<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * @conected to class ./pagination/makePagination
*/ 

require_once plugin_dir_path(__FILE__) . '../pagination/makePagination.php';


class allPayment extends makePagination
{

    public function __construct($pagination, $url, $database){ 
        parent::__construct($database);
        $this->pagination = $pagination != null ? $pagination : 1;
        $this->numOfzitemsPerPage = 10; 
        $this->url = $url;
    }



    public function tryGetAllPayment(){ 
        global $wpdb; 
        $stopValue = $this->numOfzitemsPerPage; // 3
        $fivesdrafts;
            if($stopValue){ // 3
                $offset = $stopValue * ($this->pagination - 1); 
                $fivesdrafts = self::returnListPaginated($offset, $stopValue ); 
            }
            return $fivesdrafts;   
    } 

    
 
}
 