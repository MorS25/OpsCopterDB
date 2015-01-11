<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Entity;

use OpsCopter\DB\ProjectBundle\Entity\GithubProject;
use OpsCopter\DB\Common\Tests\DatabaseKernelTestCase;

class ProjectTest extends DatabaseKernelTestCase {

    public function testCreateProject() {
        $this->createProject('foo', 'ABC', 'http://github.com/foo/bar');
    }

    /**
     * @expectedException \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    public function testCreateProjectDuplicateIdentifier() {
        $this->createProject('foo', 'foo', 'http://github.com/foo/bar');
        $this->createProject('foo', 'bar', 'http://github.com/bar/foo');
    }

    /**
     * @expectedException \Doctrine\DBAL\Exception\NotNullConstraintViolationException
     */
    public function testCreateProjectNoName() {
        $this->createProject('foo', NULL, 'http://google.com');
    }

    /**
     * @expectedException \Doctrine\DBAL\Exception\NotNullConstraintViolationException
     */
    public function testCreateProjectNoUri() {
        $this->createProject('foo', 'test', NULL);
    }

    protected function createProject($identifier, $name, $uri) {
        $project = new GithubProject($identifier);
        $project
            ->setName($name)
            ->setUri($uri);
        $this->em->persist($project);
        $this->em->flush($project);
    }

}
