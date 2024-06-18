<?php

namespace App\Controller;

use App\Form\HelloType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/',
        name: 'app_index',
    )]
    public function index(Request $request): Response
    {
        // TODO - Reprendre le nom HelloType et rendre fonctionnel le form de contact
        $form = $this->createForm(HelloType::class);

        // $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {

        //     return $this->redirectToRoute('app_index');
        // }

        if(!$this->getUser()) {
            return $this->render('index/index.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        // On redirige vers le feed si on est connecté
        else if($this->getUser()) {
            return $this->redirectToRoute('app_feed');
        }

    }
}