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
        $user = $this->getUser();
        return $this->render("main/home.html.twig", [
            'user' => $user,
            'userConnected' => $this->isConnected(),
            'isAdmin' => $this->isAdmin()
        ]);
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
            'form' => $form->createView(),
            'userConnected' => $this->isConnected(),
                        'isAdmin' => $this->isAdmin()
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
            'userConnected' => $this->isConnected(),
            'isAdmin' => $this->isAdmin()
        ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){

    }

    public function isConnected(){
        $user = $this->getUser();
        if ($user == null) {
            return false;
        }
            else {
                return true;
            }
        }

        public function isAdmin() {
            $user = $this->getUser();
            if ($user != null){
                $role = $user->getRoles();
                if ($role[0] == 'ROLE_ADMIN'){
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
}