<?php

namespace OpsCopter\DB\ServerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="server_fact")
 */
class ServerFact {

    /**
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="facts")
     * @ORM\Id
     */
    protected $server;

    /**
     * @ORM\Column(type="string")
     * @ORM\Id
     */
    protected $type;

    /**
     * @ORM\Column(type="string")
     */
    protected $value;

    public function __construct($type = NULL, $value = NULL) {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ServerFact
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
     * Set value
     *
     * @param string $value
     * @return ServerFact
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set server
     *
     * @param \OpsCopter\DB\ServerBundle\Entity\Server $server
     * @return ServerFact
     */
    public function setServer(\OpsCopter\DB\ServerBundle\Entity\Server $server)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return \OpsCopter\DB\ServerBundle\Entity\Server
     */
    public function getServer()
    {
        return $this->server;
    }
}
