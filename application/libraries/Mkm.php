<?php
require_once 'Curl.php';
class Mkm {

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
        $this->url = 'https://sandbox.mkmapi.eu/ws/v2.0/output.json/';
        $this->default_params = array(
           'oauth_consumer_key'        => MKM_APP_TOKEN,
           'oauth_token'               => MKM_ACCESS_TOKEN,
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
        $this->client = new Curl($this->url, $this->default_params);
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

        return $decode ? json_decode($result,true) : $result;
    }

    protected function get_product($id_product, $options = array(), $decode = true) {
        if (empty($id_product) && !is_numeric($id_product)) {
            return false;
        }
        list($http_code, $result) = $this->client->get('products/' . $id_product);

        if($http_code != self::HTTP_CODE_INFO) {
            throw new Exception('Error : can\'t get product\'s data.', $http_code);
        }

        return $decode ? json_decode($result, true) : $result;
    }

    // mise à jour des informations d'un article
    protected function put_article($data) {
        list($http_code, $result) = $this->client->put('stock', $this->data_to_xml($data));
        if($http_code != self::HTTP_CODE_LIST) {
            throw new Exception('Error : can\'t update article\'s data (id : ' . $data['idProduct'] .')', $http_code);
        }

        return true;
    }

    private function data_to_xml($data) {
        $xml = '<request><article>';
        foreach ($data as $k => $v) {
            $xml .= '<' . $k . '>' . $v . '</' . $k . '>';
        }
        $xml .= '</article></request>';
        return $xml;
    }

}
