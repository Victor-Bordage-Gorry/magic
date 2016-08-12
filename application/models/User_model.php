<?php

class User_model extends CI_Model {

    public $id;
    public $login;
    public $mail;
    public $password;
    public $last_login;

    public function __construct() {
        parent::__construct();
    }
}