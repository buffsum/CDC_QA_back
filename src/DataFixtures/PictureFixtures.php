<?php

namespace App\DataFixtures;

use App\DataFixtures\RestaurantFixtures;
use App\Entity\Picture;
use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;


class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @throws Exception */
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 20; $i++) {
            /** @var Restaurant $restaurant*/
            $restaurant = $this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE . random_int(1, 20));

            $picture = (new Picture())
                ->setTitle($faker->sentence())
                ->setSlug($faker->slug())
                ->setRestaurant($restaurant)
                ->setCreatedAt(new DateTimeImmutable());

            $manager->persist($picture);
            $this->addReference("picture_$i", $picture);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
        ];
    }
}
