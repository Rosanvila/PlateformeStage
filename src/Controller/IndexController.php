<?php

namespace App\Controller;

use App\Form\HelloType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route(
        path: '/{_locale}/',
        name: 'app_index',
        requirements: ['_locale' => '%app.supported_locales%']
    )]
    public function index(Request $request): Response
    {
        $form = $this->createForm(HelloType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('app_index');
        }

        return $this->render('index/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}