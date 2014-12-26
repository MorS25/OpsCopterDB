<?php

namespace OpsCopter\DB\ArtifactsBundle\Entity;

use OpsCopter\DB\ProjectBundle\Entity\Project;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="artifact")
 */
class Artifact {

    const UPLOAD_IN_PROGRESS = 1;
    const UPLOAD_COMPLETE = 2;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="OpsCopter\DB\ProjectBundle\Entity\Project", inversedBy="repositories")
     */
    protected $project;

    /**
     * @ORM\Column(type="string")
     */
    protected $filesystem;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    protected $filename;


    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $type;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $status = self::UPLOAD_IN_PROGRESS;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set filesystem
     *
     * @param string $filesystem
     * @return Artifact
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get filesystem
     *
     * @return string
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Artifact
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Artifact
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Artifact
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set project
     *
     * @param \OpsCopter\DB\ProjectBundle\Entity\Project $project
     * @return Artifact
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \OpsCopter\DB\ProjectBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }
}
