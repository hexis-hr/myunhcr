<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceSector
 *
 * @ORM\Table(name="service_sector")
 * @ORM\Entity
 */
class ServiceSector {

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
    protected $sectorName = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $sectorAcronym = "";

    /**
     * @ORM\Column(type="text")
     */
    protected $description = "";

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
     * @param mixed $description
     */
    public function setDescription ($description) {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription () {
        return $this->description;
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
     * @param mixed $sectorAcronym
     */
    public function setSectorAcronym ($sectorAcronym) {
        $this->sectorAcronym = $sectorAcronym;
    }

    /**
     * @return mixed
     */
    public function getSectorAcronym () {
        return $this->sectorAcronym;
    }

    /**
     * @param mixed $sectorName
     */
    public function setSectorName ($sectorName) {
        $this->sectorName = $sectorName;
    }

    /**
     * @return mixed
     */
    public function getSectorName () {
        return $this->sectorName;
    }

}
