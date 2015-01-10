<?php

namespace OpsCopter\DB\ProjectBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use OpsCopter\DB\ProjectBundle\Validator\Constraints as ProjectAssert;

class ProjectStub {

    /**
     * @Assert\NotBlank()
     * @ProjectAssert\ProjectUrl()
     */
    protected $uri;

    public function setUri($uri) {
        $this->uri = $uri;
    }

    public function getUri() {
        return $this->uri;
    }
}
