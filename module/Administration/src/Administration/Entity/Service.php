<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity
 */
class Service {

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
    protected $serviceName = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $serviceAcronym = "";

    /**
     * @ORM\Column(type="float")
     */
    protected $latitude = "";

    /**
     * @ORM\Column(type="float")
     */
    protected $longitude = "";

    /**
     * @ORM\Column(type="text")
     */
    protected $description = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $serviceUrl = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\ServiceOrganization")
     * @ORM\JoinColumn(name="organization", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $organization;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\ServiceSector")
     * @ORM\JoinColumn(name="sector", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $sector;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\ServiceActivity")
     * @ORM\JoinColumn(name="activity", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $activity;

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
     * @param mixed $latitude
     */
    public function setLatitude ($latitude) {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude () {
        return $this->latitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude ($longitude) {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude () {
        return $this->longitude;
    }

    /**
     * @param mixed $sector
     */
    public function setSector ($sector) {
        $this->sector = $sector;
    }

    /**
     * @return mixed
     */
    public function getSector () {
        return $this->sector;
    }

    /**
     * @param mixed $serviceAcronym
     */
    public function setServiceAcronym ($serviceAcronym) {
        $this->serviceAcronym = $serviceAcronym;
    }

    /**
     * @return mixed
     */
    public function getServiceAcronym () {
        return $this->serviceAcronym;
    }

    /**
     * @param mixed $serviceName
     */
    public function setServiceName ($serviceName) {
        $this->serviceName = $serviceName;
    }

    /**
     * @return mixed
     */
    public function getServiceName () {
        return $this->serviceName;
    }

    /**
     * @param mixed $serviceUrl
     */
    public function setServiceUrl ($serviceUrl) {
        $this->serviceUrl = $serviceUrl;
    }

    /**
     * @return mixed
     */
    public function getServiceUrl () {
        return $this->serviceUrl;
    }

    /**
     * @param mixed $organization
     */
    public function setOrganization ($organization) {
        $this->organization = $organization;
    }

    /**
     * @return mixed
     */
    public function getOrganization () {
        return $this->organization;
    }

    /**
     * @param mixed $activity
     */
    public function setActivity ($activity) {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getActivity () {
        return $this->activity;
    }

}
