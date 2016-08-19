<?php

class Product extends CI_Model {

    public $id;
    public $name;
    public $image;
    public $rarity;
    public $extension;

    public function __construct() {
        parent::__construct();
    }

    public function getAllProduct($order = array()) {
        if(!empty($order)) {
            $this->db->order_by($order['field'], $order['option']);
        }
        $query = $this->db->get('article');
        return $query->result();
    }

    public function getProduct($id) {
        $query = $this->db->get_where('product', array('id' => $id));
        return $query->row();
    }

    public function insertProduct($data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->image = $data['image'];
        $this->rarity = $data['rarity'];
        $this->extension = $data['extension'];

        $this->db->insert('product', $this);
    }

    public function updateProduct($id, $data) {
        $this->id = $id;
        $this->name = $data['name'];
        $this->image = $data['image'];
        $this->rarity = $data['rarity'];
        $this->extension = $data['extension'];

        $this->db->update('product', $this, array('id' => $id));
    }
}

//https://www.mkmapi.eu/ws/documentation/API_2.0:Entities:Product