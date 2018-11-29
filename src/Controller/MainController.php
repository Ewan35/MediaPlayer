<?php
namespace App\Controller;

use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Utilisateur;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MainController
 * @package App\Controller
 * @Route("/", name="main_")
 */
class MainController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render("main/home.html.twig");
    }

    /**
     * @Route("/register", name="register")
     */
    public function registration(Request $request,
                                 UserPasswordEncoderInterface $encoder,
                                 EntityManagerInterface $em){

        $user = new Utilisateur();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //crypter le mot de passe
            $pass = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($pass);
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('main_home');

        }

        return $this->render('main/register.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        dump($request, $lastUsername);
        return $this->render('main/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }
}