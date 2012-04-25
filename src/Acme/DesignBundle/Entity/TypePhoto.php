<?php

namespace Acme\DesignBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Acme\DesignBundle\Entity\TypePhoto
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\DesignBundle\Entity\TypePhotoRepository")
 */
class TypePhoto
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
     * @Assert\NotNull()
     */
    private $name;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable = "true" )
     */
    private $description;

    /**
     * @var integer $photos
     *
     * @ORM\OneToMany(targetEntity = "Photo", mappedBy = "type")
     */
    protected  $photos;


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
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Add photos
     *
     * @param Acme\DesignBundle\Entity\Photo $photos
     */
    public function addPhoto(\Acme\DesignBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;
    }

    /**
     * Get photos
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }
}