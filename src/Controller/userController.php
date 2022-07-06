<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class userController extends AbstractController
{

    #[Route(path: '/connexion', name: 'connexion', methods: ['GET'], schemes: ['https'])]
    function connexion()
    {

        return $this->render('connexion.html.twig');
    }

    #[Route(path: '/suscribe', name: 'suscribe', methods: ['GET'], schemes: ['https'])]
    function suscribe()
    {

        return $this->render('suscribe.html.twig');
    }
}
