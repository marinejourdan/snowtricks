<?php

namespace App\Controller;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class mediaController extends AbstractController
{
    #[Route(path: '/deleteMedia/{id}', name: 'deleteMedia', methods: ['GET', 'POST'], schemes: ['https'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function delete(Request $request, EntityManagerInterface $em)
    {
        $id = $request->get('id');
        $media = $this->getDoctrine()->getRepository(Media::class)->find($id);
        $trick = $media->getTrick()->getId();

        $form = $this->createFormBuilder()
            ->add('oui', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($media);
            $em->flush();

            $this->addFlash('success', 'Vous avez bien supprimé le media');

            return $this->redirectToRoute('update', ['id' => $trick]);
        }
        return $this->render('doDelete.html.twig', [
            'media' => $media,
            'form' => $form->createView(),
        ]);
    }
}