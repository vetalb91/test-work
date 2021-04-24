<?php
namespace Models;

use System\DB;

  class Model {
     protected $db = null;

     public function __construct()
     {
         $this->db = DB::connectToDB();
     }


 }