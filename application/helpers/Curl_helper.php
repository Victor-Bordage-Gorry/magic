<?php

class Curl_helper {

    // vars
    public  $url;
    private $options;
    private $function;
    private $method;
    private $data;

    // init.
    function __construct($url, $options) {
        $this->url = $url;
        $this->options = $options;
    }

    // post method
    function post($fun, $data = false) {
        $this->method = 'POST';
        $this->function = $fun;
        $this->data = $data;
        return $this->curlExec();
    }

    // get method
    function get($fun, $data = false) {
        $this->method = 'GET';
        $this->function = $fun;
        $this->data = $data;
        return $this->curlExec();
    }

    // put method
    function put($fun, $data = false){
        $this->method = 'PUT';
        $this->function = $fun;
        $this->data = $data;
        return $this->curlExec();
    }

    // delete method
    function delete($fun, $data = false){
        $this->method = 'DELETE';
        $this->function = $fun;
        $this->data = $data;
        return $this->curlExec();
    }

    // curl stuff
    private function curlExec(){

        // initialisation du Curl et de l'url
        $curl = curl_init();
        $url = $this->url . $this->function;

        switch ($this->method){
            case 'GET': // for regular use
            if ($this->data)
                $this->url = sprintf('%s?%s', $url, http_build_query($this->data));
            break;

            case 'POST':
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($this->data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
            break;

            case 'PUT':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($this->data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
            break;

            case 'DELETE':
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if ($this->data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
            break;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $header = $this->prepare_header($url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array($header));

        $result = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        return array($http_code, $result);
    }

    private function prepare_header($url) {

        $this->options['oauth_timestamp'] = time();
        $this->options['oauth_nonce'] = uniqid();

        // initialisation de la string de base
        $baseString = strtoupper($this->method) . "&";
        $baseString .= rawurlencode($url) . "&";

        // parsing paramètres autres que les datas
        $encodedParams = array();
        foreach ($this->options as $key => $value) {
            $encodedParams[rawurlencode($key)] = rawurlencode($value);
        }
        ksort($encodedParams);

        // ajout des valeurs préalablement encodées à la string de base
        $values = array();
        foreach ($encodedParams as $key => $value) {
            $values[] = $key . "=" . $value;
        }
        $paramsString = rawurlencode(implode("&", $values));
        $baseString .= $paramsString;

        // construction de la signature OAuth
        $signatureKey = rawurlencode(APP_SECRET) . "&" . rawurlencode(ACCESS_TOKEN_SECRET);
        $rawSignature = hash_hmac("sha1", $baseString, $signatureKey, true);
        $oAuthSignature = base64_encode($rawSignature);

        $this->options['realm'] = $url;
        $this->options['oauth_signature'] = $oAuthSignature;

        // construction du header
        $headerParams = array();
        foreach ($this->options as $key => $value)
        {
            $headerParams[] = $key . "=\"" . $value . "\"";
        }

        // on renvoi la chaine de caractère représentant le header
        return 'Authorization: OAuth '. implode(", ", $headerParams);
    }
}
