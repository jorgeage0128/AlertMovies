<?php
/**
 * Created by PhpStorm.
 * User: Jorgeage
 * Date: 15/07/17
 * Time: 1:31 PM
 */

namespace AppBundle\ServiceClients;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Unirest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TheMovieDbClient extends Controller
{
    private $URL;
    private $API_KEY;
    private $SEARCH_PERSON_URL_PART = "search/person/";
    private $SEARCH_CREDITS_URL_PART = "person/";
    private $MOVIE_CREDITS = "movie_credits";
    private $SUCCESS_CODE = 200;
    private $HEADERS = array('Accept' => 'application/json');

    public function __construct(ContainerInterface $container){
        $this->URL = $container->getParameter("movie_url_service");
        $this->API_KEY = $container->getParameter("movie_token");
    }

    public function getActors($query){
        $json = "";
        $url = $this->URL.$this->SEARCH_PERSON_URL_PART;
        $parameters = array('api_key' =>  $this->API_KEY, 'query' => $query);
        $response = Unirest\Request::get($url,$this->HEADERS,$parameters);
        if ($response->code == $this->SUCCESS_CODE) {
            $json = $response->body;
        }
        return $json;
    }

    public function getActorsCredits($actorID, $language = "en"){
        $json = "";
        $url = $this->URL.$this->SEARCH_CREDITS_URL_PART.$actorID."/".$this->MOVIE_CREDITS;
        $parameters = array('api_key' =>  $this->API_KEY, 'language' => $language);
        $response = Unirest\Request::get($url,$this->HEADERS,$parameters);
        if ($response->code == $this->SUCCESS_CODE) {
            $json = $response->body;
        }
        return $json;
    }
}