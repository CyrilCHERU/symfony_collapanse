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
    {
    }

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
        $this->makeMany(Image::class, 100, function (Image $image, $e) {

            $url = ["image1", "image2", "image3", "image4", "image5", "image6", "image7", "image8", "image9", "image10"];

            $image->setDate($this->faker->dateTimeBetween('- 10 years', 'now'))
                ->setCaption($this->faker->word(5))
                ->setUrl("./img/plaies/" . $this->faker->randomElement($url, 1) . ".jpg");

            for ($i = 0; $i < 2; $i++) {

                $image->setIntervention($this->getRandomReference(Intervention::class));
            }
        });
    }
}
