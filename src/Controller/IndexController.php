<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        if(!$this->getUser()) {
        return $this->render('index/index.html.twig');
        }
        // On redirige vers le feed si on est connecté
        else if($this->getUser()) {
            return $this->redirectToRoute('app_feed');
        }

    }
}