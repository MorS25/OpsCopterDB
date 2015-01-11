<?php

namespace OpsCopter\DB\ProjectBundle\Provider;

use OpsCopter\DB\ProjectBundle\Entity\BitbucketProject;
use OpsCopter\DB\ProjectBundle\Entity\Project;

class BitbucketProjectProvider implements ProjectProvider {

    const URI_PATTERN= '~^
        (
            ((http|https)\://(([\pL\pN-]+:)?([\pL\pN-]+)@)?bitbucket.com/)     # HTTP
            |(git\://bitbucket.com/)                                           # GIT
            |(git@bitbucket.com\:)                                             # SSH
        )
        (?P<owner>[a-zA-Z0-9_\-]+)                                          # Owner
        \/
        (?P<name>[a-zA-Z0-9_\-]+)                                           # Identifier
        (.git)?
    $~ixu';

    public function getName() {
        return 'bitbucket';
    }

    public function getEntityClass() {
        return 'OpsCopter\DB\ProjectBundle\Entity\BitbucketProject';
    }

    /**
     * @inheritDoc
     */
    public function isValidUri($uri) {
        return (bool) preg_match(static::URI_PATTERN, $uri);
    }

    public function normalizeUri($uri) {
        if($this->matchUri($uri, $matches)) {
            return sprintf('https://bitbucket.com/%s/%s', $matches['owner'], $matches['name']);
        }
        throw new \InvalidArgumentException("Invalid URI");
    }

    protected function matchUri($uri, &$matches = array()) {
        return preg_match(static::URI_PATTERN, $uri, $matches);
    }

    public function createProjectFromUri($uri) {
        if($this->matchUri($uri, $matches)) {
            return new BitbucketProject(sprintf('%s/%s', $matches['owner'], $matches['name']));
        }
    }

    public function syncProjectWithProvider(Project $project) {
        if(!$project instanceof BitbucketProject) {
            throw new \InvalidArgumentException('Not a Bitbucket Project');
        }
        throw new \Exception('Not implemented');
    }
}
