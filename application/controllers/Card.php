<?php
defined('BASEPATH') OR exit('No direct script access allowed');


Class Card extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('article', 'product'));
    }

    public function index() {
        $this->load->view('header');
        $this->load->view('index');
        $this->load->view('footer');
    }

    public function populate() {
        $this->load->library('Mkm');
        $this->mkm->init_curl();
        $mkm_stock = $this->mkm->get_stock();
        $stock = $this->article->getAllArticle();

        foreach ($mkm_stock['article'] as $mkm_article) {
            $article = $this->article->getArticle($mkm_article['idArticle']);
            $product = $this->product->getProduct($mkm_article['idProduct']);
            $mkm_product_temp = $this->mkm->get_product($mkm_article['idProduct']);
            $mkm_product = $mkm_product_temp['product'];

            $price = $mkm_product['priceGuide']['TREND'];

            // modification du prix si ce dernier est trop bas
            if ($price < MIN_PRICE_CARD) {
                $price = MIN_PRICE_CARD;
            }

            $data_product = array(
                'id' => $mkm_product['idProduct'],
                'name' => $mkm_product['enName'],
                'image' => str_replace('.', 'https://fr.magiccardmarket.eu', $mkm_product['image']),
                'rarity' => $mkm_product['rarity'],
                'extension' => $mkm_product['expansion']['enName']
            );

            $data_article = array(
                'id' => $mkm_article['idArticle'],
                'id_product' => $mkm_article['idProduct'],
                'price' => $price,
                'count' => $mkm_article['count'],
                'condition' => $mkm_article['condition'],
                'id_language' => $mkm_article['language']['idLanguage'],
                'comments' => $mkm_article['comments'],
                'is_foil' => $mkm_article['isFoil']
            );


            // gestion du produit
            if (empty($product)) {
                $this->product->insertProduct($data_product);
            }

            // gestion de l'article
            if (empty($article)) {

                // insertion de l'article
                $this->article->insertArticle($data_article);
            } else if ($article->price !== $price) {

                // mise à jour de l'article
                $this->article->updateArticle($data_article);
            }
        }

        echo "fini";exit();
    }

    public function updateOnline() {
        $this->load->library('Mkm');
        $this->mkm->init_curl();
        $stock = $this->article->getAllArticle(array('field' => 'last_modified', 'option' => 'ASC'));
        foreach ($stock as $article) {

            //mise à jour de l'article en ligne
            $this->mkm->put_article(array(
                'idArticle' => $article->id,
                'idLanguage' => $article->id_language,
                'comments' => $article->comments,
                'count' => $article->count,
                'price' => $article->price,
                'condition' => $article->condition,
                'isFoil' => $article->is_foil == '1' ? 'true' : 'false',
                'isSigned' => 'false',
                'isPlayset' => 'false'
                )
            );
        }
    }

    public function inventaire() {

    }

    public function export() {

    }

}