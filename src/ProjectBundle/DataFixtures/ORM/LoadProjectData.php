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


//        $relations = new BitbucketProject('relations');
//        $relations->setName('Relations');
//        $relations->setDescription('This project has relationships.');
//        $relations->setUri('git@github.com/owner/project');
//
//        $manager->persist($relations);
//
//        $norelations = new GithubProject('no-relations');
//        $norelations->setName('No Relations');
//        $norelations->setDescription('This project has no relationships.');
//        $norelations->setUri('git@github.com/owner/project');
//
//        $manager->persist($norelations);
//
        $manager->flush();
//
//        $this->addReference('project-relations', $relations);
    }

    public function getOrder() {
        return 1;
    }
}
