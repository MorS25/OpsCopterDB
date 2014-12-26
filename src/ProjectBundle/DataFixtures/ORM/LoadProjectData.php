<?php

namespace OpsCopter\DB\ProjectBundle\DataFixtures\ORM;

use OpsCopter\DB\ProjectBundle\Entity\Project;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $relations = new Project('relations');
        $relations->setName('Relations');
        $relations->setDescription('This project has relationships.');

        $manager->persist($relations);

        $norelations = new Project('no-relations');
        $norelations->setName('No Relations');
        $norelations->setDescription('This project has no relationships.');

        $manager->persist($norelations);

        $manager->flush();

        $this->addReference('project-relations', $relations);
    }

    public function getOrder() {
        return 1;
    }
}
