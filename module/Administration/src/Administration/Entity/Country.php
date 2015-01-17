<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country {

    /**
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\SequenceGenerator(sequenceName="id", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $countryId = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $name = "";

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    protected $languages = array();

    /**
     * @param mixed $countryId
     */
    public function setCountryId ($countryId) {
        $this->countryId = $countryId;
    }

    /**
     * @return mixed
     */
    public function getCountryId () {
        return $this->countryId;
    }

    /**
     * @param mixed $language
     */
    public function setLanguages ($language) {
        $this->languages[] = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguages () {
        return $this->languages;
    }

    public function removeLanguage ($language) {
        if(($key = array_search($language, $this->languages)) !== false)
            unset($this->languages[$key]);
        return $this->languages;
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


}
