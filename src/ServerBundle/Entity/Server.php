<?php

namespace OpsCopter\DB\ServerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="server")
 */
class Server {

    /**
     * @ORM\Id
     * @ORM\Column(type="string", unique=true, options={"comment": "A unique identifier for this machine"})
     * @Assert\NotBlank()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=256)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="ServerFact", mappedBy="server", indexBy="type", cascade={"ALL"}, orphanRemoval=true)
     */
    protected $facts;

    public function __construct($id) {
        $this->id = $id;
        $this->facts = new ArrayCollection();
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @param string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Server
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getOs() {
        return $this->getFactValue('OS');
    }

    public function getOsVersion() {
        return $this->getFactValue('OS_VERSION');
    }

    public function getHostname() {
        return $this->getFactValue('HOSTNAME');
    }

    /**
     * Add facts
     *
     * @param \OpsCopter\DB\ServerBundle\Entity\ServerFact $facts
     * @return Server
     */
    public function addFact(\OpsCopter\DB\ServerBundle\Entity\ServerFact $fact)
    {
        $fact->setServer($this);
        $this->facts[$fact->getType()] = $fact;

        return $this;
    }

    /**
     * Remove facts
     *
     * @param \OpsCopter\DB\ServerBundle\Entity\ServerFact $facts
     */
    public function removeFact(\OpsCopter\DB\ServerBundle\Entity\ServerFact $fact)
    {
        $this->facts->removeElement($fact);
    }

    /**
     * Get facts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFacts()
    {
        return $this->facts;
    }

    public function getFactValue($name) {
        return isset($this->facts[$name]) ? $this->facts[$name]->getValue() : NULL;
    }
}
