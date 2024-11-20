<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class DefaultController extends AbstractController
{
    // #[Route('/', methods: ['GET'])]
    #[Route('/')]
    public function home (): Response
    {
        // CALL LA BDD 
        //ENVOYER UN MAIL
        // CRÉER $this->render()
        return new Response(content: 'Bonjour !!');
    }
}
