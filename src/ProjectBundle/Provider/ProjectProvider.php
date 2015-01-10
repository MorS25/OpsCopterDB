<?php

namespace OpsCopter\DB\ProjectBundle\Provider;

use OpsCopter\DB\ProjectBundle\Entity\Project;

interface ProjectProvider {

    public function isValidUri($uri);

    public function normalizeUri($uri);

    public function getName();

    public function getEntityClass();

    public function createProjectFromUri($uri);

    public function syncProjectWithProvider(Project $project);
}
