<?php

class Article_model extends CI_Model {

    public $id;
    public $id_product;
    public $price;
    public $count;
    public $comment;
    public $is_foiled;
    public $last_modified;

    public function __construct() {
        parent::__construct();
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }

    public function get_all_article() {
    	$query = $this->db->get('article');
    	return $query->result();
    }

    public function get article($id) {
    	$query = $this->db->get_where('article', array('id' => $id));
    	return $query->row();
    }

    public function insert_article($data) {
    	$this->id = $data['id'];
	   	$this->id_product = $data['id_product'];
	    $this->price = $data['price'];
	    $this->count = $data['count'];
	    $this->comment = $data['comment'];
	    $this->is_foiled = $data['is_foiled'];
	    $this->last_modified = time();

	    $this->db->insert('article', $this);
    }

    public function update_article($id, $data) {
    	$this->price = $data['price'];
    	$this->count = $data['count'];
    	$this->comment = $data['comment'];
    	$this->is_foiled = $data['is_foiled'];
    	$this->last_modified = $data['last_modified'];
    }
}

// https://www.mkmapi.eu/ws/documentation/API_2.0:Entities:Article7