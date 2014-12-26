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
     * @return mixed
     */
    public function validateUploadGrantRequest(ArtifactUploadRequest $request);

    /**
     * Given a validated upload grant request, get the grant.
     *
     * @param ArtifactUploadRequest $request
     * @return mixed
     */
    public function getUploadGrant(ArtifactUploadRequest $request);

    /**
     * Validate a single download grant request.
     *
     * This function must throw an exception if something is invalid.
     *
     * @param Artifact $artifact
     * @return mixed
     */
    public function validateDownloadGrantRequest(Artifact $artifact);

    /**
     * Given a validated download grant request, get the grant.
     *
     * @param Artifact $artifact
     * @return mixed
     */
    public function getDownloadGrant(Artifact $artifact);

}
