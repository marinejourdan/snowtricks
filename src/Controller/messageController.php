<?php

namespace App\Controller;

use App\Form\MessageFormType;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;

class messageController extends AbstractController
{

    #[Route(path: '/addMessage', name: 'addMessage', methods: ['POST'], schemes: ['https'])]
    public function addMessage(Request $request){

    $myForm=$this->createForm(MessageFormType::class);

        return $this->render('oneTrick.html.twig',[
           'form'=> $myForm->createView(),]);
    }
}

