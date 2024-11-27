<?php

namespace App\DataFixtures;

use App\DataFixtures\RestaurantFixtures;
use App\Entity\Picture;
use App\Entity\Restaurant;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class PictureFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 20; $i++) {
            /** @var Restaurant $restaurant*/
            $restaurant = $this->getReference("restaurant_$i");

            $picture = (new Picture())
                ->setTitle("Image n° $i")
                ->setSlug("Slug-article-titlre")
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