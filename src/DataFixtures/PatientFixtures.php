<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Patient;
use App\DataFixtures\JobFixtures;
use App\DataFixtures\BaseFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class PatientFixtures extends BaseFixtures implements DependentFixtureInterface
{
    protected $encoder;

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
            UserFixtures::class
        ];
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->makeMany(Patient::class, 100, function (Patient $patient, $e) {

            $birthDate = $this->faker->dateTimeBetween('-80 years', '-45 years');
            $genders = [
                "Homme",
                "Femme"
            ];

            $phone = $this->faker->phoneNumber;
            if (stristr($phone, "+33") === false || stristr($phone, "+33 (0)") === false) {
                $phone = $phone;
            }
            if (stristr($phone, "+33 (0)")) {
                $phone = str_replace("+33 (0)", "0", $phone);
            } elseif (stristr($phone, "+33")) {
                $phone = str_replace("+33", "0", $phone);
            }
            $phone = str_replace(' ', "", $phone);
            $gender = $this->faker->randomElement($genders, 1);

            $patient->setGender($gender);

            if ($gender == "Homme") {
                $gender = "male";
            } elseif ($gender == "Femme") {
                $gender = "female";
            }

            $picture = 'http://randomuser.me/api/portraits/';
            $pictureId = $this->faker->numberBetween(1, 99) . '.jpg';
            if ($gender == "male") $picture = $picture . 'men/' . $pictureId;
            else $picture = $picture . 'women/' . $pictureId;

            $patient->setEmail("patient$e@gmail.com")
                ->setBirthDate($birthDate)
                ->setFirstName($this->faker->firstName($gender))
                ->setLastName($this->faker->lastName())
                ->setPhone($phone)
                ->setAddress1($this->faker->streetAddress)
                ->setZipCode('02' . $this->faker->numberBetween(000, 990))
                ->setCity(strtoupper($this->faker->city))
                ->setDoctor($this->getRandomReference("DOCTEUR"))
                ->setAvatar($picture);

            for ($i = 0; $i < mt_rand(1, 3); $i++) {

                $nurse = $this->getRandomReference("INFIRMIER");
                $patient->addNurse($nurse);
            }
        });
    }
}
