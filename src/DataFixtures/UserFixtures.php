<?php

namespace App\DataFixtures;

use App\Entity\Job;
use App\Entity\User;
use App\DataFixtures\JobFixtures;
use App\DataFixtures\BaseFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures implements DependentFixtureInterface
{
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
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
            JobFixtures::class
        ];
    }

    protected function loadData(ObjectManager $manager)
    {

        $this->makeMany(User::class, 6, function (User $user, $e) {
            $genders = [
                "Homme",
                "Femme"
            ];

            $lastName = strtoupper($this->faker->lastName());

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

            $user->setGender($gender);

            if ($gender == "Homme") {
                $gender = "male";
            } elseif ($gender == "Femme") {
                $gender = "female";
            }
            $firstName = $this->faker->firstName($gender);

            $user->setEmail("docteur$e@gmail.com")
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setPhone($phone)
                ->setAdeli("02" . $this->faker->numberBetween(0000000, 9999999))
                ->setAddress1($this->faker->streetAddress)
                ->setZipCode('02' . $this->faker->numberBetween(000, 990))
                ->setCity(strtoupper($this->faker->city))
                ->setJob($this->getReference("Docteur"))
                ->setSlug($lastName . '-' . $firstName);
        }, "DOCTEUR");

        $this->makeMany(User::class, 14, function (User $user, $e) {
            $genders = [
                "Homme",
                "Femme"
            ];

            $lastName = strtoupper($this->faker->lastName());

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

            $user->setGender($gender);

            if ($gender == "Homme") {
                $gender = "male";
            } elseif ($gender == "Femme") {
                $gender = "female";
            }
            $firstName = $this->faker->firstName($gender);

            $user->setEmail("ide$e@gmail.com")
                ->setPassword($this->encoder->encodePassword($user, 'password'))
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setPhone($phone)
                ->setAdeli("02" . $this->faker->numberBetween(0000000, 9999999))
                ->setAddress1($this->faker->streetAddress)
                ->setZipCode('02' . $this->faker->numberBetween(000, 990))
                ->setCity(strtoupper($this->faker->city))
                ->setJob($this->getReference("Infirmier(e)"))
                ->setSlug($lastName . '-' . $firstName);
        }, "INFIRMIER");
    }
}
