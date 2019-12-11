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

        foreach ($jobs as $title) {
            $job = new Job;

            $job->setTitle($title);

            $manager->persist($job);

            $manager->flush();

            $this->addReference($title, $job);
        }
    }
}
