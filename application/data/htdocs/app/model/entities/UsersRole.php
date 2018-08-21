<?php
namespace App\Model\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\SmartObject;


/**
 * @ORM\Entity
 * @ORM\Table(name="main.users_role")
 */
class UsersRole
{
    use Identifier;
    use SmartObject;

    /**
     * @ORM\Column(type="string", nullable=false, length=20, name="name", unique=true)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true, length=250, name="description")
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(type="integer", nullable=false, name="succession")
     * @var int
     */
    protected $succession;

    /**
     * @ORM\Column(type="string", nullable=false, length=25, name="css_class")
     * @var string
     */
    protected $cssClass;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return UsersRole
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return UsersRole
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getSuccession()
    {
        return $this->succession;
    }

    /**
     * @param int $succession
     * @return UsersRole
     */
    public function setSuccession($succession)
    {
        $this->succession = $succession;
        return $this;
    }

    /**
     * @return string
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @param string $cssClass
     * @return UsersRole
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
        return $this;
    }


}