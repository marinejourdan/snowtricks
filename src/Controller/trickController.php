<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Entity\Message;
use App\Form\MessageFormType;

class trickController extends AbstractController
{

    #[Route(path: '/addTrick', name: 'index', methods: ['GET|POST'], schemes: ['https'])]



    function addTrick(Request $request ){
        $trick=New Trick;

        $form=$this->createFormBuilder($trick)
            ->add('name', TextType::class)
            ->add('descritpion', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        return $this->render('accueil.html.twig', [

        ]);
    }


    #[Route(path: '/addTrick', name:'trick', methods: ['GET','POST'], schemes: ['https'])]

    function add(Request $request ){
        $trick=New Trick;

        $form=$this->createFormBuilder($trick)
            ->add('name', TextType::class)
            ->add('descritpion', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        return $this->render('addTrick.html.twig', [
            'myform'=> $form->createView()
        ]);
    }

    #[Route(path: '/oneTrick', name: 'messages', methods: ['GET|POST'], schemes: ['https'])]
    public function getOne(Request $request){

        $trick=[
            [
                'name'=>'snwprout',
                'description'=>' super ce trick, vraiment genbaim'
            ]
        ];

        $messages=[
            [
                'content'=>'proutprout',
                'creation_date'=>'date ok',
                'author'=>' Jean eudes'
            ]
        ];

        $myForm=$this->createForm(MessageFormType::class);
        $myForm->handleRequest($request);
        if ($myForm-> isSubmitted()&& $myForm-> isValid()) {

        }

        return $this->render('oneTrick.html.twig',[
            'trick'=> $trick,
            'messages'=> $messages,
            'myform' => $myForm->createView(),
        ]);

    }
}

