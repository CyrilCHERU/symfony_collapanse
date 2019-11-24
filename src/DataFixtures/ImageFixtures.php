<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Intervention;
use App\DataFixtures\BaseFixtures;
use App\DataFixtures\InterventionFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ImageFixtures extends BaseFixtures implements DependentFixtureInterface
{


    public function __construct()
    { }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            InterventionFixtures::class
        ];
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->makeMany(Image::class, 3, function (Image $image, $e) {

            $image->setDate($this->faker->dateTimeBetween('- 10 years', 'now'))
                ->setCaption($this->faker->word(5))
                ->setUrl("http://localhost:8000/img/image_$e");

            for ($i = 0; $i < mt_rand(1, 3); $i++) {

                $image->setIntervention($this->getRandomReference(Intervention::class));
            }
        });
    }
}
