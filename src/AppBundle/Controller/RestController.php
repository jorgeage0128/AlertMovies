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
     * Metodo que se encarga de consumir servicio que consulta el listado de actores a partir
     * de una cadena.
     * @Rest\Get("/api/actor/{query}")
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
     * @Rest\Get("/api/actorMovies/{actorID}")
     * @param $actorID
     * @return View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getActorMovies($actorID)
    {
        //TODO: colocar el consumo del servicio
        try{
            $movieClient = new TheMovieDbClient($this->container);
            return $this->json($movieClient->getActorsCredits($actorID));
        } catch (\Exception $ex) {
            return new View("Actor not found", Response::HTTP_NOT_FOUND);
        }
    }
}