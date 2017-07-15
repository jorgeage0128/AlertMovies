<?php
/**
 * Created by PhpStorm.
 * User: Jorgeage
 * Date: 15/07/17
 * Time: 11:02 AM
 */

namespace AppBundle\Controller;


use AppBundle\ServiceClients\TheMovieDbClient;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class RestController extends FOSRestController
{
    /**
     * Consumes de MovieDb Service through the client class
     * @Rest\Get("/api/actor/{query}", defaults={"query"=""})
     * @param $query
     * @return View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getActor($query)
    {
        try{
            $movieClient = new TheMovieDbClient($this->container);
            return $this->json($movieClient->getActors($query));
        } catch (\Exception $ex) {
            return $this->json($ex, 500);
        }
    }

    /**
     * Consumes de MovieDb Service through the client class
     * @Rest\Get("/api/actorMovies/{actorID}", defaults={"actorID"=""})
     * @param $actorID
     * @return View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getActorMovies($actorID)
    {
        try{
            $movieClient = new TheMovieDbClient($this->container);
            return $this->json($movieClient->getActorsCredits($actorID));
        } catch (\Exception $ex) {
            return $this->json($ex, 500);
        }
    }
}