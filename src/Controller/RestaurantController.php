<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    // toutes la class "RestaurantController" va recevoir ces deux dépendances 
    // "EntityManagerInterface" pour envoyer de l'info en bdd et "RestaurantRepository" pour récupérer de l'info en bdd
    public function __construct(private EntityManagerInterface $manager, private RestaurantRepository $repository) // ou $restaurantRepository ?
    {
    }
    #[Route(name: 'new', methods: 'POST')] 
    // new / show / edit / delete sont mes fonctions de CRUD
    public function new(): Response
    {
        $restaurant = new Restaurant(); // bien définir 'Restaurant' dans le use comme 'use App\Entity\Restaurant;'
        $restaurant->setName(name: 'Quai Antique 4');
        $restaurant->setDescription(description: 'test');
        $restaurant->setCreatedAt(new \DateTimeImmutable());
        $restaurant->setMaxGuest(40);
        // $restaurant->setAmOpeningTime('13:00');
        // $restaurant->setPmOpeningTime('19:00');

        // À stocker en base (donc via EntityManagerInterface)
        $this->manager->persist($restaurant); // fil d'attente où tous les nouveaux objets doivent listé par Doctrine via persist()
        $this->manager->flush(); // puis on va push ça dans la bdd via flush()

        return $this->json(
            ['message' => "Restaurant créé {$restaurant->getId()} id"],
            Response::HTTP_CREATED,
        );
    }
    #[Route('/{id}', name: 'show', methods: 'GET')]
    public function show(int $id): Response
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        // $restaurant = CHERCHER LE RESTAURANT ID = 1 ($id) (donc via RestaurantRepository)
        if (!$restaurant) {
            // throw $this->createNotFoundException("No restaurant found for id $id");
            throw new \Exception("No restaurant found for id $id");
        }
        // même chose que d'écrire : return new JsonResponse(['message' => 'Restaurant de ma BDD']);
        return $this->json(
            ['message' => "A restaurant was found : ' {$restaurant->getName()} with id # {$restaurant->getId()}"]
        );
    }
    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id): Response
    {
        // pour l'instant en dur mais plus tard en dynamique avec la BDD
        if (!$restaurant) {
            throw new \Exception("No restaurant found for id $id");
        }

        $restaurant->setName('Restaurant name update');

        return $this->redirectToRoute('app_api_restaurant_show', ['id' => $restaurant->getId()]);
    }
    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        if (!$restaurant) {
            throw new \Exception("No restaurant found for id $id");
        }

        return $this->json(['message' => 'Restaurant deleted'], Response::HTTP_NO_CONTENT);
    }
}



// <?php

// // src/Controller/RestaurantController.php
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// #[Route('/api/restaurant', name: 'app_api_restaurant_')]
// class RestaurantController extends AbstractController
// {
//     #[Route('/{id}', name: 'edit', methods: 'PUT')]
//     public function edit(int $id): Response
//     {
//         // … Edite le restaurant et le sauvegarde en base de données
//     }

//     #[Route('/{id}', name: 'delete', methods: 'DELETE')]   
//     public function delete(int $id): Response
//     {
//         // ... Supprime le restaurant de la base de données
//     }
// }