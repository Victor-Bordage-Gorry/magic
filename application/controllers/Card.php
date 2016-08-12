<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class Card extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('header');
        $this->load->view('index');
        $this->load->view('footer');
    }

    public function inventaire() {

    }

    public function export() {

    }

    public function

}