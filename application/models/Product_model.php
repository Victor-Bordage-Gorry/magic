<?php

class Product_model extends CI_Model {

    public $id;
    public $name;
    public $image;
    public $rarity;
    public $extension;

    public function __construct() {
        parent::__construct();
    }
}

//https://www.mkmapi.eu/ws/documentation/API_2.0:Entities:Product