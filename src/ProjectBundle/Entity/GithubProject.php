<?php

namespace OpsCopter\DB\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class GithubProject extends Project {

    public function __construct($identifier) {
        $this->identifier = $identifier;
    }
}
