<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Fixtures;

use OpsCopter\DB\ProjectBundle\Entity\Project;
use OpsCopter\DB\ProjectBundle\Provider\ProjectProvider;
use OpsCopter\DB\ProjectBundle\Entity\GithubProject;

class DummyProjectProvider implements ProjectProvider {

    protected $uriPattern;

    protected $name;

    protected $entityClass;

    public function __construct($name = 'dummy', $uriPattern = '/.*/', $entityClass = 'OpsCopter\DB\ProjectBundle\Entity\GithubProject') {
        $this->uriPattern = $uriPattern;
        $this->name = $name;
        $this->entityClass = $entityClass;
    }

    public function isValidUri($uri) {
        return preg_match($this->uriPattern, $uri);
    }

    public function normalizeUri($uri) {
        return $uri;
    }

    public function getName() {
        return $this->name;
    }

    public function getEntityClass() {
        return $this->entityClass;
    }

    public function createProjectFromUri($uri) {
        $class = $this->entityClass;
        return new $class($uri);
    }

    public function syncProjectWithProvider(Project $project) {
        $project->setName('name');
        $project->setDescription('description');
        $project->setUri($project->getIdentifier());
    }


}
