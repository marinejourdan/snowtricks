<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trick;
use App\Entity\Message;
use App\Entity\Group;
use App\Form\MessageFormType;
use Doctrine\ORM\EntityManagerInterface;

class trickController extends AbstractController
{


    #[Route(path: '/', name:'index', methods: ['GET'], schemes: ['https'])]
    public function getList(){

        $repo=$this->getDoctrine()->getRepository(Trick::class);
        $tricks=$repo->findAll();

        return $this-> render('accueil.html.twig', ['tricks'=>$tricks]);
    }

    #[Route(path: '/add', name:'trick', methods: ['GET','POST'], schemes: ['https'])]

    function add(Request $request,EntityManagerInterface $em){

        $trick=New Trick;

        $form=$this->createFormBuilder($trick)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
        ;

        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $trick=$form->getData();
            $em-> persist($trick);
            $em->flush();
        }
        return $this->render('addTrick.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    #[Route(path: '/trick/details/{id}', name: 'trick', methods: ['GET|POST'], schemes: ['https'])]
    public function getOne(Request $request, EntityManagerInterface $em){

        $id=$request->get('id');
        $trickRepo= $this->getDoctrine()->getRepository(Trick::class);
        $trick=$trickRepo->find($id);


        $repo= $this->getDoctrine()->getRepository(Message::class);
        $messages=$repo->findAll();


        $message=New Message();
        $message->setTrick($trick);
        $myForm=$this->createForm(MessageFormType::class, $message);
        $myForm->handleRequest($request);
        if ($myForm-> isSubmitted()&& $myForm-> isValid()) {
            $message->setcontent(0);
            //$message->setauthor(New User);
            $message->setCreationDate(New \DateTime);

            $em-> persist($message);
            $em->flush();

            //return $this-> redirectToRoute('messages');


        }

        return $this->render('oneTrick.html.twig',[
            'trick'=> $trick,
            'messages'=> $messages,
            'myform' => $myForm->createView(),
        ]);

    }

    #[Route(path: '/delete/{id}', name: 'delete', methods: ['GET|POST'], schemes: ['https'])]
        public function doDelete(Request $request, EntityManagerInterface $em){


            $id=$request->get('id');
            $trickRepo= $this->getDoctrine()->getRepository(Trick::class);
            $trick=$trickRepo->find($id);

            $em= $this->getDoctrine()->getRepository(Trick::class);
            $em->remove($trick);

        return $this->render('doDelete.html.twig');
    }
}

