<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed')]
    public function feed(): Response
    {
        return $this->render('feed/index.html.twig');

    }
}