<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Reponse};
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private SerializerInterface $serializer)
    {
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    /**
     * @OA\Post(
     *      path="/api/register",
     *      summary="Inscription d'un nouvel utilisateur / Register a new user",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Données de l'utilisateur à inscrire / User data to register",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="adresse@mail.com"),
     *              @OA\Property(property="password", type="string", example="Password123!")
     *          )
     *      ),
     *      @OA\Response(
     *        response=201,
     *        description="Utilisateur inscrit avec succès / User registered successfully",
     *        @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user", type="string", example="adresse@mail.com"),
     *              @OA\Property(property="apiToken", type="string", example="cuez53fdGZfd62"),
     *              @OA\Property(property="roles", type="array", @OA\Items(type="string", example="ROLE_USER"))
     *         )
     *      )
     *  )
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse(
            ['user' => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    /**
     * @OA\Post(
     *      path="/api/login",
     *      summary="Connexion d'un utilisateur / User login",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Données de l'utilisateur à connecter / User data to login",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="username", type="string", example="adresse@mail.com"),
     *              @OA\Property(property="password", type="string", example="Password123!")
     *          )
     *      ),
     *      @OA\Response(
     *        response=200,
     *        description="Utilisateur connecté avec succès / User logged in successfully",
     *        @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="user", type="string", example="adresse@mail.com"),
     *              @OA\Property(property="apiToken", type="string", example="cuez53fdGZfd62"),
     *              @OA\Property(property="roles", type="array", @OA\Items(type="string", example="ROLE_USER"))
     *         )
     *      )
     *  )
     */
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }
        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ]);
    }
}
