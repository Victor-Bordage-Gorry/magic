<?php

class MKM_helper {

    private $url;
    private $default_params = array();
    private $client;

    const HTTP_CODE_LIST = 200;
    const HTTP_CODE_CREATE = 201;
    const HTTP_CODE_INFO = 200;
    const HTTP_CODE_UPDATE = 200;
    const HTTP_CODE_DELETE = 204;

    /**
     * Initialisation de l'objet
     *
     */
    public function __construct() {
        $this->url = 'https://sandbox.mkmapi.eu/ws/v1.1/output.json/';
        $this->default_params = array(
           'oauth_consumer_key'        => APP_TOKEN,
           'oauth_token'               => ACCESS_TOKEN,
           'oauth_signature_method'    => 'HMAC-SHA1',
           'oauth_version'             => '1.0'
        );
    }

    public function __call($method, $arguments) {
        if (method_exists($this, $method)) {
            if ($method !== 'init_curl') {
                $this->init_curl();
            }
            return call_user_func_array(array($this, $method), $arguments);
        }
    }

    /**
     * Fonction d'initialisation du client Curl
     */
    private function init_curl() {
        $this->client = new CURL_Client($this->url, $this->default_params);
    }

    /**
     * Fonction de récupération du stock de l'utilisateur
     *
     * @param  array   $options     ajout d'options pour la récupération des données (facultatif)
     * @param  boolean $decode      permet de choisir si le json récupéré doit être décodé ou non (facultatif)
     * @return array / string       tableau ou string json des résultats de la requête MKM
     */
    protected function get_stock($options = array(), $decode = true) {
        list($http_code, $result) = $this->client->get('stock');

        if($http_code != self::HTTP_CODE_LIST) {
            throw new Exception('Error : can\'t get user\'s stock.', $http_code);
        }

        return $decode ? json_decode($result) : $result;
    }

    protected function get_product($id_product, $options = array(), $decode = true) {
        if (empty($id_product) && !is_numeric($id_product))
            return false;

        list($http_code, $result) = $this->client->get('product/' . $id_product);

        if($http_code != self::HTTP_CODE_INFO) {
            throw new Exception('Error : can\'t get product\'s data.', $http_code);
        }

        return $decode ? json_decode($result) : $result;
    }
}
