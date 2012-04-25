<?php

namespace Acme\DesignBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Imagine;

/**
 * Acme\DesignBundle\Entity\Photo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Acme\DesignBundle\Entity\PhotoRepository")
 */
class Photo
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
     * @var integer $type
     *
     * @ORM\ManyToOne(targetEntity = "TypePhoto", inversedBy = "photos" )
     * @ORM\JoinColumn(name = "type_id", referencedColumnName = "id", onDelete="CASCADE")
     */
    protected $type;

    /**
     * @var integer $object
     *
     * @ORM\ManyToOne(targetEntity = "Object", inversedBy = "photos" )
     * @ORM\JoinColumn(name = "object_id", referencedColumnName = "id", onDelete="CASCADE")
     */
    protected $object;

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
        return 'uploads/photos';
    }

    static function upload_dir($request = false) {
        $this_ = new self;
        return $request->server->get('DOCUMENT_ROOT').'/web/'.$this_->getUploadDir();
    }

    public function upload($request = false)
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

        //миниатюра
//        echo '<pre>';
//        echo var_dump($request->server->get('DOCUMENT_ROOT'));die;

        $upload_dir = self::upload_dir($request);
        $imagine = new Imagine\Gd\Imagine();
        $image = $imagine->open($upload_dir.'/'.$random_name);
        $thumbnail = $image->thumbnail(new Imagine\Image\Box(200, 200));
        $thumbnail->save($upload_dir.'/thumb/'.$random_name);
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


    public function __toString() {
        return $this->name;
    }

    /**
     * Set type
     *
     * @param Acme\DesignBundle\Entity\TypePhoto $type
     */
    public function setType(\Acme\DesignBundle\Entity\TypePhoto $type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return Acme\DesignBundle\Entity\TypePhoto 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set object
     *
     * @param Acme\DesignBundle\Entity\Object $object
     */
    public function setObject(\Acme\DesignBundle\Entity\Object $object)
    {
        $this->object = $object;
    }

    /**
     * Get object
     *
     * @return Acme\DesignBundle\Entity\Object 
     */
    public function getObject()
    {
        return $this->object;
    }
}