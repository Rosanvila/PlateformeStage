<?php

namespace App\Controller;

use App\Form\PreferencesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PreferencesController extends AbstractController
{
    #[Route('/preferences', name: 'app_preferences')]
    public function index(TranslatorInterface $translator): Response
    {
        $form = $this->createForm(PreferencesType::class);


        return $this->render('preferences/index.html.twig', [
            'controller_name' => 'PreferencesController',
            'form' => $form->createView(),
        ]);
    }
}
