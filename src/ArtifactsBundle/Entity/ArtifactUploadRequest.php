<?php

namespace OpsCopter\DB\ArtifactsBundle\Entity;

use OpsCopter\DB\ProjectBundle\Entity\Project;
use Symfony\Component\Validator\Constraints as Assert;

class ArtifactUploadRequest {

    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *  pattern="/^[a-z0-9]([a-z0-9\-\.]*[a-z0-9])?$/"
     * )
     */
    protected $filename;

    /**
     * @Assert\NotBlank()
     */
    protected $project;

    protected $replace;

    public function setFilename($filename) {
        $this->filename = $filename;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function setProject(Project $project) {
        $this->project = $project;
    }

    public function getProject() {
        return $this->project;
    }

    public function setReplace($replace) {
        $this->replace = (bool) $replace;
    }

    public function getReplace() {
        return $this->replace;
    }
}
