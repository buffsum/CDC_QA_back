<?php

namespace App\DataFixtures;

use App\DataFixtures\PictureFixtures;
use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $restaurant = (new Restaurant())
                ->setName("Restaurant n° $i")
                ->setDescription("Description Restaurant n° $i")
                ->setAmOpeningTime([])
                ->setPmOpeningTime([])
                ->setMaxGuest(rand(10, 50))
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($restaurant);
            $this->addReference("restaurant_$i", $restaurant);
        }
        $manager->flush();
    }
}
