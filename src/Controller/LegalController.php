<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LegalController extends AbstractController
{
    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('legal/mentions_legales.html.twig');
    }

    #[Route('/cgu', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('legal/cgu.html.twig');
    }

    #[Route('/cgv', name: 'app_cgv')]
    public function cgv(): Response
    {
        return $this->render('legal/cgv.html.twig');
    }

    #[Route('/politique-de-confidentialite', name: 'app_privacy')]
    public function privacy(): Response
    {
        return $this->render('legal/confidentialite.html.twig');
    }

    #[Route('/qui-sommes-nous', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('legal/qui_sommes_nous.html.twig');
    }
}
