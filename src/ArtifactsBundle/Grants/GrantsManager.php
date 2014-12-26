<?php

namespace OpsCopter\DB\ArtifactsBundle\Grants;

use OpsCopter\DB\ArtifactsBundle\Entity\Artifact;
use OpsCopter\DB\ArtifactsBundle\Entity\ArtifactUploadRequest;

interface GrantsManager {

    /**
     * Validate a single upload grant request.
     *
     * This function must throw an exception if something is invalid.
     *
     * @param ArtifactUploadRequest $request
     * @return boolean
     */
    public function validateUploadGrantRequest(ArtifactUploadRequest $request);

    /**
     * Given a validated upload grant request, get the grant.
     *
     * @param ArtifactUploadRequest $request
     * @return \OpsCopter\DB\ArtifactsBundle\Entity\ArtifactGrant
     */
    public function getUploadGrant(ArtifactUploadRequest $request);

    /**
     * Validate a single download grant request.
     *
     * This function must throw an exception if something is invalid.
     *
     * @param Artifact $artifact
     * @return boolean
     */
    public function validateDownloadGrantRequest(Artifact $artifact);

    /**
     * Given a validated download grant request, get the grant.
     *
     * @param Artifact $artifact
     * @return \OpsCopter\DB\ArtifactsBundle\Entity\ArtifactGrant
     */
    public function getDownloadGrant(Artifact $artifact);

}
