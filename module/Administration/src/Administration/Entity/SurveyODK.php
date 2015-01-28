<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * SurveyODK
 *
 * @ORM\Table(name="survey_odk")
 * @ORM\Entity
 */
class SurveyODK {

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
     * @ORM\Column(type="string")
     */
    protected $url = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Country")
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
     * @param mixed $url
     */
    public function setUrl ($url) {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl () {
        return $this->url;
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
