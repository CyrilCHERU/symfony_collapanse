<?php

namespace App\DataFixtures;

use App\Entity\Care;
use App\Entity\Patient;
use App\DataFixtures\BaseFixtures;
use App\DataFixtures\PatientFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class CareFixtures extends BaseFixtures implements DependentFixtureInterface
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
            PatientFixtures::class
        ];
    }

    protected function loadData(ObjectManager $manager)
    {

        $this->makeMany(Care::class, 150, function (Care $care, $e) {

            $wounds = ["Uclère Jambe Droite", "Ulcère Jambe Gauche", "Escare Sacrée", "Escare Talonière", "Mal Perforant Plantaire", "Nécrose osseuse Orteil Pied Droit", "Nécrose osseuse Orteil Pied Gauche", "Ulcères Bilattéraux sur les deux Jambes"];

            $care->setCreatedAt($this->faker->dateTimeBetween('-10 years', 'now'))
                ->setEndedAt($this->faker->dateTimeBetween('-10 years', 'now'))
                ->setWoundType($this->faker->randomElement($wounds, 1));

            for ($i = 0; $i < mt_rand(0, 80); $i++) {
                $care->setPatient($this->getRandomReference(Patient::class));
            }
        });
    }
}
