<?php

namespace App\DataFixtures;

use App\DataFixtures\PictureFixtures;
use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker ;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RestaurantFixtures extends Fixture
{
    public const RESTAURANT_REFERENCE = "restaurant";
    public const RESTAURANT_NB_TUPLES = 20; // nombre de resto max
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 1;$i <= self::RESTAURANT_NB_TUPLES; $i++) {
            $restaurant = (new Restaurant())
                ->setName($faker->company())
                ->setDescription($faker->text())
                ->setAmOpeningTime([])
                ->setPmOpeningTime([])
                ->setMaxGuest(rand(10, 50))
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($restaurant);
            $this->addReference(self::RESTAURANT_REFERENCE . $i, $restaurant);
        }
        $manager->flush();
    }
}
