<?php

/**
 * design patern function :
 * 
 * pattern 1:
 * public function nama_function(data_type $param = NULL, data_type $param1 = NULL, ...){}
 * 
 * pattern 2:
 * public function nama_funciton(){
 *      $param1 = $_GET['param1];
 *      $param2 = $_GET['param2];
 *      $param3 = $_GET['param3];
 * }
 */

namespace App\Http\Controllers;

abstract class Controller
{
    // 
}
