<?php

namespace OpsCopter\DB\ProjectBundle\DataFixtures\ORM;

use OpsCopter\DB\ProjectBundle\Entity\BitbucketProject;
use OpsCopter\DB\ProjectBundle\Entity\GithubProject;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface {

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $project1 = new GithubProject('rbayliss/OpsCopterDB');
        $project1->setName('OpsCopterDB');
        $project1->setDescription('A github project');
        $project1->setUri('https://github.com/rbayliss/OpsCopterDB');
        $manager->persist($project1);
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }
}
