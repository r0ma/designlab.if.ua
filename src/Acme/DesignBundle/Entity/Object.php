<?php

namespace Acme\DesignBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Acme\DesignBundle\Entity\Object
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\DesignBundle\Entity\ObjectRepository")
 */
class Object
{

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $photos
     *
     * @ORM\OneToMany(targetEntity = "Photo", mappedBy = "object")
     */
    private $photos;

    /**
     * @var integer $type
     *
     * @ORM\ManyToOne(targetEntity = "TypeObject", inversedBy = "objects", cascade={"persist"})
     * @ORM\JoinColumn(name="typeobject_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $type;

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
     * @Assert\File(maxSize="6000000")
     */
    private $file;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // абсолютный путь к каталогу, куда будут сохраняться загруженные документы
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        //избавьтесь от __ DIR __, так чтобы его не было, когда отображался загруженный документ/изображение
        return 'uploads/prew_project/';
    }


    public function upload()
    {
        // свойство file может быть пустым, если поле не обязательное
        if (null === $this->file) {
            return;
        }

        // тут используется исходное имя файла, но вы должны, по крайней мере,
        // обработать его, чтобы избежать любых проблем с безопасностью

        // метод move принимает целевой каталог и имя файла для перемещения
        // Оригинальное название здесь - $this->file->getClientOriginalName()

        $extension = $this->file->guessExtension();

        if(!$extension) {
            $extension = 'bin';
        }

        $random_name = 'photo_'.rand(1, 999999999).'.'.$extension;

        $this->file->move($this->getUploadRootDir(), $random_name);

        // установка свойства path на значение имени файла, где он был сохранен
        $this->setPath($random_name);

        // очистка свойства file, поскольку оно более не понадобится
        $this->file = null;
    }

    /**
     * Set file
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set path
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }




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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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

    /**
     * Set type
     *
     * @param Acme\DesignBundle\Entity\TypeObject $type
     */
    public function setType(\Acme\DesignBundle\Entity\TypeObject $type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return Acme\DesignBundle\Entity\TypeObject 
     */
    public function getType()
    {
        return $this->type;
    }
}