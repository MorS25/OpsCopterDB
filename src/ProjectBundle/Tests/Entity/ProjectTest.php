<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Entity;

use OpsCopter\DB\ProjectBundle\Entity\Project;
use OpsCopter\DB\Common\Tests\DatabaseKernelTestCase;
use OpsCopter\DB\Common\Utility\ControllerGetters;

class ProjectTest extends DatabaseKernelTestCase {
    use ControllerGetters;

    public function testCreateProject() {
        $project = new Project('abc');
        $project->setName('ABC');
        $this->em->persist($project);
        $this->em->flush();
    }

    /**
     * @expectedException \Doctrine\DBAL\Exception\NotNullConstraintViolationException
     */
    public function testCreateProjectNoName() {
        $project = new Project('abc');
        $this->em->persist($project);
        $this->em->flush();
    }

    /**
     * @expectedException \Doctrine\ORM\ORMException
     */
    public function testCreateProjectNoIdentifier() {
        $project = new Project(NULL);
        $project->setName('ABC');
        $this->em->persist($project);
        $this->em->flush();
    }

    public function testChangeProjectIdentifier() {
        $project = $this->getProject('relations');
        $project->setId('changed');
        $this->em->persist($project);

        $this->em->flush();
    }
}
