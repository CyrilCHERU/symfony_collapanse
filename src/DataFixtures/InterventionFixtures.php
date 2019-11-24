<?php

namespace App\DataFixtures;

use App\Entity\Care;
use App\Entity\Intervention;
use App\DataFixtures\BaseFixtures;
use App\DataFixtures\CareFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class InterventionFixtures extends BaseFixtures implements DependentFixtureInterface
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
            CareFixtures::class
        ];
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->makeMany(Intervention::class, 200, function (Intervention $inter, $e) {

            $inter->setDate($this->faker->dateTimeBetween('- 10 years', 'now'))
                ->setComment($this->faker->paragraph(6));

            for ($i = 0; $i < mt_rand(4, 10); $i++) {
                $inter->setCare($this->getRandomReference(Care::class));
            }
        });
    }
}
