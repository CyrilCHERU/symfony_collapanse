<?php

namespace App\DataFixtures;



use Faker\Factory;
use Faker\Generator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BaseFixtures extends Fixture
{
    /**
     * Variable de génération d'un faker, livrable partout
     *
     * @var Generator
     */
    protected $faker;

    /**
     * Variable d'objectManager livrable partout
     *
     * @var ObjectManager
     */
    protected $manager;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // on se passe les propriétés en privé, la fonction load agit presque comme un constructeur
        $this->faker = $faker;

        $this->manager = $manager;

        $this->loadData($manager);

        $manager->flush();
    }

    /**
     * Fonction visantant à être utilisée dans les fixtures filles
     *
     * @param ObjectManager $manager
     * @return void
     */
    protected function loadData(ObjectManager $manager)
    { }

    /**
     * Fonction servant à créer plusieurs éléments d'une entité
     *
     * @param string $entityClassName
     * @param integer $limit
     * @param callable $factory
     * @return void
     */
    protected function makeMany(string $entityClassName, int $limit, callable $factory, $customReference = null)
    {
        // creer des entités
        for ($e = 0; $e < $limit; $e++) {

            $entity = new $entityClassName;

            $factory($entity, $e);

            $this->manager->persist($entity);

            if ($customReference) {
                $this->setReference($customReference . '_' . $e, $entity);
            }
            $this->setReference($entityClassName . '_' . $e, $entity);
        }
    }

    /**
     * Fonction servant à obtenir une référence de l'entité déjà créée
     *
     * @param string $className
     */
    protected function getRandomReference(string $className)
    {
        $entities = [];
        $iterator = 0;

        while ($this->hasReference($className . "_" . $iterator)) {

            $reference = $this->getReference($className . "_" . $iterator);
            $entities[] = $reference;

            $iterator++;
        }

        return $this->faker->randomElement($entities);
    }
}
