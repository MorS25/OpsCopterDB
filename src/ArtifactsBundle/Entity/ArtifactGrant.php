<?php

namespace OpsCopter\DB\ArtifactsBundle\Entity;

class ArtifactGrant {

    protected $filesystem;

    protected $credentials;

    protected $filename;

    public function __construct($filesystem, $credentials, $filename) {
        $this->filesystem = $filesystem;
        $this->credentials = $credentials;
        $this->filename = $filename;
    }
}
