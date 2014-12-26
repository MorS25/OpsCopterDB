<?php

namespace OpsCopter\DB\ArtifactsBundle\Grants;

use Aws\S3\Exception\NoSuchKeyException;
use Aws\S3\S3Client;
use Aws\Sts\StsClient;
use OpsCopter\DB\ArtifactsBundle\Entity\Artifact;
use OpsCopter\DB\ArtifactsBundle\Entity\ArtifactGrant;
use OpsCopter\DB\ArtifactsBundle\Entity\ArtifactUploadRequest;
use Symfony\Component\Validator\Exception\ValidatorException;

class S3GrantsManager implements GrantsManager {

    /**
     * @var \Aws\S3\S3Client
     */
    protected $s3Client;

    /**
     * @var \Aws\Sts\StsClient
     */
    protected $stsClient;

    /**
     * The name of the bucket to use
     *
     * @var string
     */
    protected $bucket;

    /**
     * The role ID in Amazon.
     *
     * @var string
     */
    protected $roleArn;

    public function __construct(S3Client $s3Client, StsClient $stsclient, $bucket, $roleArn) {
        $this->s3Client = $s3Client;
        $this->stsClient = $stsclient;
        $this->bucket = $bucket;
        $this->roleArn = $roleArn;
    }

    public function validateUploadGrantRequest(ArtifactUploadRequest $request) {
        $key = basename($request->getFilename());
        if(!$request->getReplace() && !$this->doesObjectExist($this->bucket, $key)) {
            throw new ValidatorException('Artifact already exists.');
        }
        return TRUE;
    }

    public function getUploadGrant(ArtifactUploadRequest $request) {
        $project = $request->getProject();
        $key = $project->getId() . '/' . basename($request->getFilename());
        $credentials = $this->getTemporaryCredentials('PutObject', $this->bucket, $key);
        $filename = "s3://{$this->bucket}/{$key}";
        return new ArtifactGrant('S3', $credentials, $filename);
    }

    public function validateDownloadGrantRequest(Artifact $artifact) {
        list($bucket, $key) = explode('/', substr($artifact->getFilename(), 5), 2);
        return $this->doesObjectExist($bucket, $key);
    }

    public function getDownloadGrant(Artifact $artifact) {
        list($bucket, $key) = explode('/', substr($artifact->getFilename(), 5), 2);
        $credentials = $this->getTemporaryCredentials('GetObject', $bucket, $key);

        return new ArtifactGrant('S3', $credentials, $artifact->getFilename());
    }

    protected function doesObjectExist($bucket, $key) {
        try {
            $this->s3Client->headObject(array(
                'Bucket' => $this->bucket,
                'Key' => $key,
            ));
            return TRUE;
        }
        catch(NoSuchKeyException $e) {
            return FALSE;
        }
    }

    /**
     * @param string $op
     */
    protected function getTemporaryCredentials($op, $bucket, $key) {
        $response = $this->stsClient->assumeRole(array(
            'RoleArn' => $this->roleArn,
            'RoleSessionName' => 'test-session-1',
            'DurationSeconds' => 900,
            'Policy' => $this->getPolicy($op, $bucket, $key),
        ));
        return $this->stsClient->createCredentials($response);
    }

    protected function getPolicy($op, $bucket, $key) {
        return json_encode(array(
            'Version' => '2012-10-17',
            'Statement' => array(
                array(
                    'Effect' => 'Allow',
                    'Action' => array(
                        "s3:{$op}"
                    ),
                    'Resource' => array("arn:aws:s3:::{$bucket}/{$key}")
                )
            )
        ));
    }
}
