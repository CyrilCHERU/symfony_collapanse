<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends BaseFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $jobs = [
            "Docteur",
            "Infirmier(e)"
        ];

        $this->makeMany(Job::class, 2, function (Job $job) use ($jobs) {

            $job->setTitle($this->faker->randomElement($jobs, 1));
        });
    }
}
