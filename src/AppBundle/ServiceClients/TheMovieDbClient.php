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
    /*
     * The Web Service URL Base
     */
    private $URL;

    /*
     * The API Key Service for moviesdb
     */
    private $API_KEY;

    /*
     * Constant for URL in searh person
     */
    private $SEARCH_PERSON_URL_PART = "search/person/";

    /*
     * Constant for URL in person credits
     */
    private $SEARCH_CREDITS_URL_PART = "person/";

    /*
     * Constant for person credits.
     */
    private $MOVIE_CREDITS = "movie_credits";

    /*
     * Constant for HTTP OK Code status
     */
    private $SUCCESS_CODE = 200;

    /*
     * Constant for headers.
     */
    private $HEADERS = array('Accept' => 'application/json');

    /**
     * This construct.
     * @param ContainerInterface $container hold the URL and token keys from parameter file
     */
    public function __construct(ContainerInterface $container){
        $this->URL = $container->getParameter("movie_url_service");
        $this->API_KEY = $container->getParameter("movie_token");
    }

    /**
     * @param $query the needle for search de actor
     * @return mixed|string json, empty json for errors
     */
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

    /**
     * @param $actorID ID actor used to get his credits.
     * @param string $language language
     * @return mixed|string json, empty json for errors
     */
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