<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// class HomeController extends AbstractController
// {
//     // #[Route('/', methods: ['GET'])]
//     #[Route('/')]
//     public function home (): Response
//     {
//         // CALL LA BDD
//         //ENVOYER UN MAIL
//         // CRÉER $this->render()
//         return new Response(content: 'Bonjour !!');
//     }
// }
class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function home(): Response
    {
        return $this->render('front/index.html.twig');
        // return @App/templates/home.html.twig
    }
    #[Route('/galerie', name: 'galerie', methods: ['GET'])]
    public function galerie(): Response
    {
        return $this->render('front/galerie.html.twig');
    }
}
