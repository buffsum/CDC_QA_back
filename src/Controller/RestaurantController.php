<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use DateTimeImmutable;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


#[Route('api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    // toutes la class "RestaurantController" va recevoir ces deux dépendances
    // "EntityManagerInterface" pour envoyer de l'info en bdd et "RestaurantRepository" pour récupérer de l'info en bdd
    public function __construct(
        private EntityManagerInterface $manager,
        private RestaurantRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
        )
        {
    }

    #[Route(methods: 'POST')]
    /**
     * @OA\Post(
     *      path="/api/restaurant",
     *      summary="Créer un nouveau restaurant / Create a new restaurant",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Données du restaurant à créer / Restaurant data to create",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Nom du restaurant"),
     *              @OA\Property(property="description", type="string", example="Description du restaurant"),
     *              @OA\Property(property="maxGuest", type="integer", example=40)
     *          )
     *      ),
     *      @OA\Response(
     *        response=201,
     *        description="Restaurant créé avec succès / Restaurant created successfully",
     *        @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="Nom du restaurant"),
     *              @OA\Property(property="description", type="string", example="Description du restaurant"),
     *              @OA\Property(property="createdAt", type="string", format="date-time")
     *         )
     *      )
     *  )
     */
    public function new(Request $request): JsonResponse
    {
        $restaurant = $this->serializer->deserialize($request->getContent(), Restaurant::class, 'json');
        $restaurant->setCreatedAt(new DateTimeImmutable());  // Correction ici
        
        $this->manager->persist($restaurant); // liste d'attente, on attend le flush pour envoyer en bdd. seulement pour new
        $this->manager->flush(); // on push en bdd

        $responseData = $this->serializer->serialize($restaurant, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_restaurant_show',
             ['id' => $restaurant->getId()],
             UrlGeneratorInterface::ABSOLUTE_URL
            );
            
            return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    /**
     * @OA\Get(
     *      path="/api/restaurant/{id}",
     *      summary="Afficher un restaurant par son ID / Show a restaurant by its ID",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du restaurant à afficher / ID of the restaurant to show",
     *         @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *        response=200,
     *        description="Restaurant trouvé avec succès / Restaurant found successfully",
     *        @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", example=1),
     *              @OA\Property(property="name", type="string", example="Nom du restaurant"),
     *              @OA\Property(property="description", type="string", example="Description du restaurant"),
     *              @OA\Property(
     *                  property="amOpeningTime", 
     *                  type="array", 
     *                  @OA\Items(type="string"),
     *                  example={"12h00", "12h30"}
     *              ),
     *              @OA\Property(
     *                  property="pmOpeningTime", 
     *                  type="array", 
     *                  @OA\Items(type="string"),
     *                  example={"19h00", "19h30"}
     *              ),
     *              @OA\Property(property="maxGuest", type="integer", example=40),
     *              @OA\Property(property="createdAt", type="string", format="date-time"),
     *              @OA\Property(property="updatedAt", type="string", format="date-time")
     *         )
     *      ),
     *      @OA\Response(
     *        response=404,
     *        description="Restaurant non trouvé / Restaurant not found"
     *      )
     *  )
     */
    public function show(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if ($restaurant) {
            $responseData = $this->serializer->serialize($restaurant, 'json');

            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    /**
     * @OA\Put(
     *      path="/api/restaurant/{id}",
     *      summary="Modifier un restaurant par son ID / Edit a restaurant by its ID",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du restaurant à modifier / ID of the restaurant to edit",
     *         @OA\Schema(type="integer")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Données du restaurant à modifier / Restaurant data to update",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Nouveau nom du restaurant"),
     *              @OA\Property(property="description", type="string", example="Nouvelle description"),
     *              @OA\Property(
     *                  property="amOpeningTime", 
     *                  type="array", 
     *                  @OA\Items(type="string"),
     *                  example={"12h00", "12h30"}
     *              ),
     *              @OA\Property(
     *                  property="pmOpeningTime", 
     *                  type="array", 
     *                  @OA\Items(type="string"),
     *                  example={"19h00", "19h30"}
     *              ),
     *              @OA\Property(property="maxGuest", type="integer", example=40)
     *          )
     *      ),
     *      @OA\Response(
     *        response=204,
     *        description="Restaurant modifié avec succès / Restaurant updated successfully"
     *      ),
     *      @OA\Response(
     *        response=404,
     *        description="Restaurant non trouvé / Restaurant not found"
     *      ),
     *      @OA\Response(
     *        response=400,
     *        description="Données invalides / Invalid data"
     *      )
     * )
     */
    public function edit(int $id, Request $request): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if ($restaurant) {
            $restaurant = $this->serializer->deserialize(
                $request->getContent(),
                Restaurant::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant]
            );
            $restaurant->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if ($restaurant) {
            $this->manager->remove($restaurant);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
