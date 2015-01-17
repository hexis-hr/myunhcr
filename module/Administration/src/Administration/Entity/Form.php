<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Form
 *
 * @ORM\Table(name="form")
 * @ORM\Entity
 */
class Form {

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
    protected $name = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $title = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $formName = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\File")
     * @ORM\JoinColumn(name="file", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $file;

    /**
     * @param mixed $file
     */
    public function setFile ($file) {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile () {
        return $this->file;
    }

    /**
     * @param mixed $country
     */
    public function setCountry ($country) {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCountry () {
        return $this->country;
    }

    /**
     * @param mixed $formName
     */
    public function setFormName ($formName) {
        $this->formName = $formName;
    }

    /**
     * @return mixed
     */
    public function getFormName () {
        return $this->formName;
    }

    /**
     * @param mixed $id
     */
    public function setId ($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId () {
        return $this->id;
    }

    /**
     * @param mixed $name
     */
    public function setName ($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName () {
        return $this->name;
    }

    /**
     * @param mixed $title
     */
    public function setTitle ($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle () {
        return $this->title;
    }

}
