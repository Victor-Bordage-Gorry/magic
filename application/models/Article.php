<?php

class Article extends CI_Model {

    public $id;
    public $id_product;
    public $price;
    public $count;
    public $condition;
    public $id_language;
    public $comments;
    public $is_foil;
    public $last_modified;

    public function __construct() {
        parent::__construct();
    }

    public function getAllArticle($order = array()) {
        if(!empty($order)) {
            $this->db->order_by($order['field'], $order['option']);
        }
        $query = $this->db->get('article');
        return $query->result();
    }

    public function getArticle($id) {
        $query = $this->db->get_where('article', array('id' => $id));
        return $query->row();
    }

    public function insertArticle($data) {
        $this->hydrate($data);
        $this->db->insert('article', $this);
    }

    public function updateArticle($data) {
        $this->hydrate($data);
        $this->db->update('article', $this, array('id' => $data['id']));
    }

    private function hydrate($data) {
        $this->id = $data['id'];
        $this->id_product = $data['id_product'];
        $this->price = $data['price'];
        $this->count = $data['count'];
        $this->condition = $data['condition'];
        $this->id_language = $data['id_language'];
        $this->comments = $data['comments'];
        $this->is_foil = $data['is_foil'];
        $this->last_modified = time();
    }
}

// https://www.mkmapi.eu/ws/documentation/API_2.0:Entities:Article7