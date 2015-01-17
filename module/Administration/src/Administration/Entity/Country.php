<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Country
 *
 * @ORM\Table(name="survey")
 * @ORM\Entity
 */
class Survey {

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
     * @ORM\Column(type="boolean")
     */
    protected $active = 1;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Form")
     * @ORM\JoinColumn(name="form", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $form = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\CodeCountries")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

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
     * @param mixed $form
     */
    public function setForm ($form) {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    public function getForm () {
        return $this->form;
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
     * @param mixed $active
     */
    public function setActive ($active) {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getActive () {
        return $this->active;
    }

}
