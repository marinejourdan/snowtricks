<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class trickController extends AbstractController
{

    #[Route(path: '/', name: 'index', methods: ['GET'], schemes: ['https'])]

    function GetList(){
        $product = [
            'name'=> 'salto',
            'description'=>'jidfiqokiPFOJQPOKJQFPO'
            ];
        return $this->render('accueil.html.twig', [
            'product'=> $product
        ]);
    }

    #[Route(path: '/trick', name:'trick', methods: ['GET'], schemes: ['https'])]

    function GetOne(){

        return $this->render('oneTrick.html.twig');
    }
}

