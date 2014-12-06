<?php

namespace Administration\Entity;

use Zend\Form\Annotation;

use Doctrine\ORM\Mapping as ORM;


/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("User")
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */

class User {

    /**
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\SequenceGenerator(sequenceName="id", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $email = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $salt = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $password = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $type = "";


    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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
     */
    public function setPassword($plaintextPassword, $salt = FALSE)
    {
        if(!$salt) $salt = uniqid();
        $this->setSalt($salt);
        $this->password = crypt($plaintextPassword, '$5$rounds=5000$'.$salt.'$');
        return $this;
    }

    public static function hashPassword($player, $password)
    {
        return ($player->getPassword() === crypt($password, $player->getPassword()));
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @param string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


}
