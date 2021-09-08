<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{   
    /**
     * Permet d'afficher le formulaire d'authentification
     *
     * @return Response
     */
    #[Route('/login', name: 'account_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();        
        
        return $this->render('account/login.html.twig', [
            'hasError' => $error != null,
            'username' => $username
        ]);
    }

    /**
     * Permet de faire la deconnexion
     * @Route("/logout", name="account_logout")
     *
     * @return Response
     */
    public function logout(): Response
    {
        // rien 
    }

    /**
     * Permet d'afficher le formulaire d'inscription
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été créé ! Vous pouvez maintenant vous connectez'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et de traiter le formulaire de modification du profile
     * 
     * @Route("/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request): Response
    {
        // récupère l'utilisateur courant ou connecté
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les informations sur votre profile ont été modifiées avec succès !'
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     * 
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $passwordUpdate = new PasswordUpdate();

        // récupère l'utilisateur courant ou connecté
        $user = $this->getUser();
        
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            if(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())){
                $form->get('oldPassword')->addError(new FormError("votre ancien password n'est pas le bon"));
            } else {
                $manager = $this->getDoctrine()->getManager();
    
                $hash = $encoder->encodePassword($user, $passwordUpdate->getNewPassword());
                $user->setPassword($hash);
    
                $manager->persist($user);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Votre Password a bien été modifié avec succès, veuillez vous reconnectez !'
                );
    
                return $this->redirectToRoute('account_logout');
            }

        }
        
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}