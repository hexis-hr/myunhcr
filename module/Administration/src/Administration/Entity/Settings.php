<?php

namespace Administration\Entity;

use Zend\Form\Annotation;

use Doctrine\ORM\Mapping as ORM;


/**
 * Settings
 *
 * @ORM\Table(name="settings")
 * @ORM\Entity
 */

class Settings {

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
    protected $settingsKey = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $settingsValue = "";

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $settingsKey
     */
    public function setSettingsKey ($settingsKey) {
        $this->settingsKey = $settingsKey;
    }

    /**
     * @return mixed
     */
    public function getSettingsKey () {
        return $this->settingsKey;
    }

    /**
     * @param mixed $settingsValue
     */
    public function setSettingsValue ($settingsValue) {
        $this->settingsValue = $settingsValue;
    }

    /**
     * @return mixed
     */
    public function getSettingsValue () {
        return $this->settingsValue;
    }

}
