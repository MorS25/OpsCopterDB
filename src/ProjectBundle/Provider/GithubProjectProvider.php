<?php

namespace OpsCopter\DB\ProjectBundle\Provider;

use Github\Client;
use OpsCopter\DB\ProjectBundle\Entity\GithubProject;
use OpsCopter\DB\ProjectBundle\Entity\Project;

class GithubProjectProvider implements ProjectProvider {

    const URI_PATTERN= '~^
        (http|https|git)\://                    # Protocol
        (([\pL\pN-]+:)?([\pL\pN-]+)@)?          # basic auth
        github.com                              # Domain
        \/
        (?P<owner>[a-zA-Z0-9_\-]+)              # Owner
        \/
        (?P<name>[a-zA-Z0-9_\-]+)         # Identifier
        (.git)?
    $~ixu';

    public function __construct() {
        $this->githubClient = new Client();
    }

    public function setClient(Client $githubClient) {
        $this->githubClient = $githubClient;
    }

    public function getName() {
        return 'github';
    }

    public function getEntityClass() {
        return 'OpsCopter\DB\ProjectBundle\Entity\GithubProject';
    }

    /**
     * @inheritDoc
     */
    public function isValidUri($uri) {
        return (bool) preg_match(static::URI_PATTERN, $uri);
    }

    public function normalizeUri($uri) {
        if($this->matchUri($uri, $matches)) {
            return sprintf('https://github.com/%s/%s', $matches['owner'], $matches['name']);
        }
        throw new \InvalidArgumentException("Invalid URI");
    }

    protected function matchUri($uri, &$matches = array()) {
        return preg_match(static::URI_PATTERN, $uri, $matches);
    }

    public function createProjectFromUri($uri) {
        if($this->matchUri($uri, $matches)) {
            return new GithubProject(sprintf('%s/%s', $matches['owner'], $matches['name']));
        }
    }

    public function syncProjectWithProvider(Project $project) {
        if(!$project instanceof GithubProject) {
            throw new \InvalidArgumentException('Not a Github Project');
        }
        $identifier = $project->getIdentifier();
        list($owner, $name) = explode('/', $identifier);

        $repo = $this->githubClient->repository()->show($owner, $name);
        $project->setName($repo['name']);
        $project->setUri($repo['clone_url']);
        $project->setDescription($repo['description']);

        return $project;
    }
}
