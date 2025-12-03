<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        Security $security,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Encode password
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $plainPassword)
            );

            $user->setRoles(['ROLE_USER']);

            // Save user to DB
            $entityManager->persist($user);
            $entityManager->flush();

            // Send verification email
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('omarallagui94@gmail.com', 'e learning'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // Auto-login
            return $security->login($user, UserAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (!$id) {
            return $this->render('registration/verify_email.html.twig', [
                'error' => 'Invalid verification link.'
            ]);
        }

        $user = $userRepository->find($id);

        if (!$user) {
            return $this->render('registration/verify_email.html.twig', [
                'error' => 'User not found.'
            ]);
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            return $this->render('registration/verify_email.html.twig', [
                'error' => $exception->getReason()
            ]);
        }

        return $this->render('registration/verify_email.html.twig', [
            'error' => null
        ]);
    }
}
