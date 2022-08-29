<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route(path: '/connexion', name: 'connexion', methods: ['GET', 'POST'], schemes: ['https'])]
    public function connexion(AuthenticationUtils $authenticationUtils, requestStack $requestStack)
    {
        $session = $requestStack->getSession();
        $session->get(name: 'username');
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();
        $user = new User();

        return $this->render('connexion.html.twig', [
            'error' => $error,
            'username' => $username,
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('index');
            $session->getFlashBag()->add('success', 'vous vous êtes bien authentifié');
        }
    }

    #[Route(path: '/suscribe', name: 'suscribe', methods: ['GET', 'POST'], schemes: ['https'])]
    public function suscribe(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            $user->setToken(md5(uniqid()));

            // Get uploaded file data
            $uploadedFile = $user->getAvatar()->getUploadedFile();
            // Get uploadedFile original filename
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            // Generate unique filename to store it in filesystem
            $fileName = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $user->getAvatar()->setFileName($fileName);
            $user->getAvatar()->setType('image');

            try {
                $uploadedFile->move(
                    'assets',
                    $fileName
                );
            } catch (FileException $e) {
                throw new \Exception('Unable to move file into assets directory.');
            }

            $em->persist($user);
            $em->flush();
            $email = (new TemplatedEmail())
                ->from(new Address('erfqfr@gmail.com'))
                ->to($form->get('email')->getData())
                ->subject('Lien de validation de votre compte')
                ->htmlTemplate('validation.html.twig')
                ->context([
                    'token' => $user->getToken(),
                    'mail' => $form->get('email')->getData(),
                ]);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                throw new \Exception('Unable to send email');
            }
            $this->addFlash('success', 'votre inscription a bien été prise en compte, un email de validation va vous être envoyé');

            return $this->redirectToRoute('index');
        }

        return $this->render('suscribe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route(path: '/activate/{token}', name: 'activate', methods: ['GET'], schemes: ['https'])]
    public function activate(Request $request, UserRepository $userRepo)
    {
        $token = $request->get('token');
        // On verifie si un utilisateur a ce token
        $user = $userRepo->findOneBy(['token' => $token]);

        // Si aucun utilisateur n'existe avec ce token
        if (!$user) {
            // Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $user->setActive(true);
        $user->setToken('');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash('message', 'Votre compte a bien été activé, connectez-vous !');

        return $this->redirectToRoute('index');
    }

    #[Route(path: '/logout', name: 'deconnexion', methods: ['GET', 'POST'], schemes: ['https'])]
    public function logOut()
    {
        return $this->redirectToRoute('index');
    }

    #[Route('/reset-password', name: 'reset-password')]
    public function resetPassword(Request $request, MailerInterface $mailer)
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneByEmail($form->getData()['email']);

            if (!$user) {
                $request->getSession()->getFlashBag()->add('warning', "Cet email n'existe pas.");

                return $this->redirectToRoute('reset-password');
            }
            $user->setToken(md5(uniqid()));
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Un email de validation vient de vous être envoyé!');

            $email = (new TemplatedEmail())
                ->from(new Address('erfqfr@gmail.com'))
                ->to($form->get('email')->getData())
                ->subject('changement de mot de passe snowtricks')
                ->htmlTemplate('lien_new_password.html.twig')
                ->context([
                    'token' => $user->getToken(),
                    'mail' => $form->get('email')->getData(),
                ]);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                throw new \Exception('Unable to send email');
            }

            return $this->redirectToRoute('connexion');
        }

        return $this->render('reset_password.html.twig', [
              'form' => $form->createView(),
          ]);
    }

    #[Route('/new-password/{token}', name: 'new-password', methods: ['GET', 'POST'], schemes: ['https']) ]
    public function newPassword(Request $request, UserRepository $userRepo, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        $form = $this->createForm(NewPasswordType::class);
        $token = $request->get('token');
        // On verifie si un utilisateur a ce token
        $user = $userRepo->findOneBy(['token' => $token]);

        if (!$user) {
            // Erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

            $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            $user->setToken('');
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'changement de mot de passe ok');

            return $this->redirectToRoute('connexion');
        }

        return $this->render('new_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
