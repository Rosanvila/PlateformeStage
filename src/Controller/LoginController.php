<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(
        path: '/{_locale}/login',
        name: 'app_login',
        requirements: ['_locale' => '%app.supported_locales%']
    )]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        // $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render('login/index.html.twig', [
            // 'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route(
        path: '/{_locale}/logout',
        name: 'app_logout',
        requirements: ['_locale' => '%app.supported_locales%']
    )]
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}