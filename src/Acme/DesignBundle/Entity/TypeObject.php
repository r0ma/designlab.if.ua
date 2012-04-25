<?php

namespace Acme\DesignBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Acme\DesignBundle\Entity\TypeObject
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\DesignBundle\Entity\TypeObjectRepository")
 */
class TypeObject
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable = "true")
     */
    private $description;

    /**
     * @var integer $objects
     *
     * @ORM\OneToMany(targetEntity = "Object", mappedBy = "type", cascade={"persist"})
     */
    private $objects;


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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function __construct()
    {
        $this->objects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }



    /**
     * Add objects
     *
     * @param Acme\DesignBundle\Entity\Object $objects
     */
    public function addObject(\Acme\DesignBundle\Entity\Object $objects)
    {
        $this->objects[] = $objects;
    }

    /**
     * Get objects
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getObjects()
    {
        return $this->objects;
    }
}