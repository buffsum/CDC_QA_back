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
    #[Route('/account', name: 'account', methods: ['GET'])]
    public function account(): Response
    {
        return $this->render('front/Auth/account.html.twig');
    }

    #[Route('/editPassword', name: 'editPassword', methods: ['GET'])]
    public function editPassword(): Response
    {
        return $this->render('front/Auth/editPassword.html.twig');
    }

    #[Route('/signin', name: 'signin', methods: ['GET'])]
    public function signin(): Response
    {
        return $this->render('front/Auth/signin.html.twig');
    }

    #[Route('/allResa', name: 'allResa', methods: ['GET'])]
    public function allResa(): Response
    {
        return $this->render('front/CRUD/allResa.html.twig');
    }

    #[Route('/reserver', name: 'reserver', methods: ['GET'])]
    public function reserver(): Response
    {
        return $this->render('front/CRUD/reserver.html.twig');
    }
}
