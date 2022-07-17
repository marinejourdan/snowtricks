<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class userController extends AbstractController
{

    #[Route(path: '/connexion', name: 'connexion', methods: ['GET','POST'], schemes: ['https'])]
    public function connexion(AuthenticationUtils $authenticationUtils, RequestStack $requestStack)
    {
        //$session=$requestStack->getSession();
      //$session->get('username');

        $error=$authenticationUtils->getLastAuthenticationError();
        $username=$authenticationUtils->getLastUsername();
        $user=new User();

        return $this->render('connexion.html.twig', [
            'error'=>$error,
            'username'=>$username
        ]);
    }

    #[Route(path: '/suscribe', name: 'suscribe', methods: ['GET','POST'], schemes: ['https'])]
    function suscribe(Request $request,EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer)
    {
        $user=new User();
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $user=$form->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setToken(md5(uniqid()));
            $em-> persist($user);
            $em->flush();

            $email = (new TemplatedEmail())
                ->from(new Address('erfqfr@gmail.com'))
                ->to($form->get('email')->getData())
                ->subject('Lien de validation de votre compte')
                ->htmlTemplate('validation.html.twig')
                ->context([
                    'token' => $user->getToken(),
                    'mail' => $form->get('email')->getData()
                ]);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
               dd('youhou');
            }

            return $this->redirectToRoute('index');
        }

        return $this->render('suscribe.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    #[Route(path: '/activate/{token}', name: 'activate', methods: ['GET'], schemes: ['https'])]
    public function activate(Request $request, UserRepository $userRepo)
    {
        $token=$request->get('token');
        //On verifie si un utilisateur a ce token
        $user = $userRepo->findOneBy(['token' => $token]);

        //Si aucun utilisateur n'existe avec ce token
        if (!$user) {
            //Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        //On supprime le token
        $user->setActive(true);
        $user->setToken('');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash('message', 'Votre compte a bien été activé, connectez-vous !');

        return $this->redirectToRoute('index');
    }

}
