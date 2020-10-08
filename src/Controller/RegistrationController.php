<?php

namespace App\Controller;

use App\Entity\User;
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
//            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//                (new TemplatedEmail())
//                    ->from(new Address('macquet.theodore@gmail.com', 'theo'))
//                    ->to($user->getEmail())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('registration/confirmation_email.html.twig')
//            );
            $signedUrl= 'app_verify_email';
            $expiresAt =new \DateTime();
            $message=(new \Swift_Message())
                ->setfrom('hello@example.com')
                ->setto('macquet.theodore@gmail.com')
                ->setsubject('Time for Symfony Mailer!')
                ->setBody(  $this->renderView('registration/confirmation_email.html.twig', array('expiresAt'=>$expiresAt,'user'=>$this->getUser(),'role'=>'ROLE_USER')),'text/html');
            // do anything else you need here, like send an email
            $mailer->send($message);
//            return $this->redirectToRoute('homepage');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/confirmation/email/{role}/{user}", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        dump($request->get('role'));
        dump($request->get('user'));
        dd();
        $this->denyAccessUnlessGranted('ROLE_USER');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
            $this->addFlash('success', 'Your email address has been verified.');
            return $this->redirectToRoute('app_register');

        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates

    }
}
