<?php

namespace App\Controller;

use App\Form\MessageFormType;
use App\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route(path: '/addMessage', name: 'addMessage', methods: ['POST'], schemes: ['https'])]
    public function addMessage(Request $request)
    {
        $myForm = $this->createForm(MessageFormType::class);

        return $this->render('oneTrick.html.twig', [
           'form' => $myForm->createView(), ]);
    }

        #[Route(path: '/loadMoreMsg', name: 'loadMoreMsg', methods: ['GET|POST'], schemes: ['https'])]
        public function loadMoreMsg(Request $request)
    {
        $msgRepo = $this->getDoctrine()->getRepository(Message::class);
        $page = $request->get('page');
        $limit = 3;
        $offset = 3 * $page - 1;

        $messages = $msgRepo->findBy([], [], $limit, $offset);

        return $this->render('loadMoreMsg.html.twig', [
            'messages' => $messages,
            'page' => $page,
        ]);
    }
}