<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Entity;

use OpsCopter\DB\ProjectBundle\Entity\GithubProject;
use OpsCopter\DB\Common\Tests\DatabaseKernelTestCase;

class ProjectTest extends DatabaseKernelTestCase {

    public function testCreateProject() {
        $project = new GithubProject('abc');
        $project->setName('ABC');
        $project->setUri('http://google.com');
        $this->em->persist($project);
        $this->em->flush();
    }

    /**
     * @expectedException \Doctrine\DBAL\Exception\NotNullConstraintViolationException
     */
    public function testCreateProjectNoName() {
        $project = new GithubProject('abc');
        $project->setUri('http://google.com');
        $this->em->persist($project);
        $this->em->flush();
    }

    /**
     * @expectedException \Doctrine\DBAL\Exception\NotNullConstraintViolationException
     */
    public function testCreateProjectNoUri() {
        $project = new GithubProject('abc');
        $project->setName('ABC');
        $this->em->persist($project);
        $this->em->flush();
    }

}
