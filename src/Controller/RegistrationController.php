<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\users;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
//            $user->setRoles( ) ;


            $entityManager->persist($user);
            $entityManager->flush();
            $expiresAt =new \DateTime();
            $message=(new \Swift_Message('inscription'))
                ->setfrom('macquet.theodore@gmail.com')
                ->setto('macquet.theodore@gmail.com')
                ->setBody(  $this->renderView('registration/confirmation_email.html.twig', array('expiresAt'=>$expiresAt,'user'=>$user->getId(),'role'=>'ROLE_USER')),'text/html');
            // do anything else you need here, like send an email
            $mailer->send($message);
//            return $this->redirectToRoute('homepage');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $repositoryUser = $this->getDoctrine()->getRepository(User::class);
        $user = $repositoryUser->find($request->get('user'));
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request,$this->getUser());
            $this->addFlash('success', 'votre adresse à était vérifier.');
            return $this->redirectToRoute('app_register');

        } catch (VerifyEmailExceptionInterface $exception) {
            dump($exception);
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates

    }
}
