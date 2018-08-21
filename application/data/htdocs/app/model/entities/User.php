<?php
namespace App\Model\Entities;

use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;

/**
 * @ORM\Entity
 * @ORM\Table(name="main.users")
 */
class User
{
    use Identifier;
    use SmartObject;

    /**
     * @ORM\Column(type="string", name="email", nullable=false)
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=false, name="password")
     * @var string
     */
    protected $password;


    /**
     * @ORM\Column(type="boolean", nullable=false, name="deleted", options={"default":0})
     * @var bool
     */
    protected $deleted = FALSE;

    /**
     * @ORM\Column(type="string", nullable=false, name="name")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=false, name="surname")
     * @var string
     */
    protected $surname;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UsersRole")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * @var UsersRole
     */
    protected $role;

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     * @return User
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return UsersRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param UsersRole $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getConcatedName()
    {
        $text = '';
        if ('' != $this->getEmail()) {
            $text .= " - " . $this->getEmail();
        }
        return $this->getFullname() . $text;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->getName() . " " . $this->getSurname();
    }

}