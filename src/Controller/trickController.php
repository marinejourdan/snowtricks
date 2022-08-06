<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\AddTrickType;
use App\Form\MessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class trickController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'], schemes: ['https'])]
    public function getList()
    {
        $repo = $this->getDoctrine()->getRepository(Trick::class);
        $tricks = $repo->findAll();

        return $this->render('accueil.html.twig', ['tricks' => $tricks]);
    }

    #[Route(path: '/add', name: 'add', methods: ['GET', 'POST'], schemes: ['https'])]
    #[Route(path: '/update/{id}', name: 'update', methods: ['GET', 'POST'], schemes: ['https'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Request $request, EntityManagerInterface $em, ?int $id)
    {
        if ($id) {
            $trickRepo = $this->getDoctrine()->getRepository(Trick::class);
            $trick = $trickRepo->find($id);
        } else {
            $trick = new Trick();
        }
        $form = $this->createForm(AddTrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medias = $form->get('medias')->getData();

            foreach ($medias as $media) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            }

            $trick = $form->getData();
            $em->persist($trick);
            $em->flush();
            $this->addFlash('success', 'Vous avez bien ajouté le trick');
        }

        return $this->render('addTrick.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/trick/details/{slug}', name: 'trick', methods: ['GET|POST'], schemes: ['https'])]
    public function getOne(Request $request, EntityManagerInterface $em)
    {
        $slug = $request->get('slug');
        $trickRepo = $this->getDoctrine()->getRepository(Trick::class);
        $trick = $trickRepo->findOneBySlug($slug);

        $repo = $this->getDoctrine()->getRepository(Message::class);
        $messages = $repo->findAll();

        $message = new Message();
        $message->setTrick($trick);
        $myForm = $this->createForm(MessageFormType::class, $message);
        $myForm->handleRequest($request);
        if ($myForm->isSubmitted() && $myForm->isValid()) {
            $message=$myForm->getData();
            $message->setAuthor($this->getUser());
            $message->setCreationDate(new \DateTime());
            $em->persist($message);
            $em->flush();

            // return $this-> redirectToRoute('messages');
        }

        return $this->render('oneTrick.html.twig', [
            'trick' => $trick,
            'messages' => $messages,
            'myform' => $myForm->createView(),
        ]);
    }

    #[Route(path: '/trick/delete/{id}', name: 'delete', methods: ['GET|POST'], schemes: ['https'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Request $request, EntityManagerInterface $em)
    {
        $id = $request->get('id');
        $trick = $this->getDoctrine()->getRepository(Trick::class)->find($id);

        $form = $this->createFormBuilder()
            ->add('oui', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($trick);
            $em->flush();

            return $this->redirectToRoute('index');
            $this->addFlash('success', 'Vous avez bien supprimé le trick');
        }

        return $this->render('doDelete.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/trickUp', name: 'TrickUp', methods: ['GET|POST'], schemes: ['https'])]
    public function accueilTest(Request $request)
    {
        return new Response('<h2>voilà '.$request->query->get('page').'</h2>');
    }
}
