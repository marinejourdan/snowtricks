<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Trick;
use App\Form\AddTrickType;
use App\Form\MessageFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class trickController extends AbstractController
{
    #[Route(path: '/', name: 'index', methods: ['GET'], schemes: ['https'])]
    public function getList()
    {
        $repo = $this->getDoctrine()->getRepository(Trick::class);
        $tricks = $repo->findBy([], [], 5);

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
            
            dd($form->getData());

            $galleries = $form->get('gallery')->getData();

            foreach ($galleries as $gallery) {
                $uploadedFile = $trick->getGallery()->getUploadedFile();
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $fileName = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $trick->getGallery()->setFileName($fileName);
                $trick->getGallery()->setType('image');
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
        $message = new Message();
        $message->setTrick($trick);
        $messages = $repo->findBy(
            ['trick' => $trick],
            ['creationDate' => 'desc']
        );

        $myForm = $this->createForm(MessageFormType::class, $message);
        $myForm->handleRequest($request);

        if ($myForm->isSubmitted() && $myForm->isValid()) {

            $message = $myForm->getData();
            if ($message->setAuthor($this->getUser())==null){
                return $this-> redirectToRoute('connexion');
            }
            $message->setAuthor($this->getUser());
            $message->setCreationDate(new \DateTime());
            $em->persist($message);
            $em->flush();
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

    #[Route(path: '/moretricks', name: 'moreTricks', methods: ['GET|POST'], schemes: ['https'])]
    public function moreTricks(Request $request)
    {
        $trickRepo = $this->getDoctrine()->getRepository(Trick::class);
        $page = $request->get('page');

        $limit = 5;
        $offset = 4 * $page - 1;

        $tricks = $trickRepo->findBy([], [], $limit, $offset);

        return $this->render('loadMore.html.twig', [
            'tricks' => $tricks,
            'page' => $page,
        ]);
    }
}
