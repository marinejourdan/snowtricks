<?php

namespace App\Controller;

use App\Form\MessageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class messageController extends AbstractController
{
    #[Route(path: '/addMessage', name: 'addMessage', methods: ['POST'], schemes: ['https'])]
    public function addMessage(Request $request)
    {
        $myForm = $this->createForm(MessageFormType::class);

        return $this->render('oneTrick.html.twig', [
           'form' => $myForm->createView(), ]);
    }
}
