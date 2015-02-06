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
     * @ORM\Column(type="text")
     */
    protected $address = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $email = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $phone = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $latitude = "";

    /**
     * @ORM\Column(type="string")
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
     * @ORM\ManyToOne(targetEntity="Administration\Entity\ServicePartner")
     * @ORM\JoinColumn(name="partner", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $partner;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * @param mixed $address
     */
    public function setAddress ($address) {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress () {
        return $this->address;
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
     * @param mixed $email
     */
    public function setEmail ($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail () {
        return $this->email;
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
     * @param mixed $partner
     */
    public function setPartner ($partner) {
        $this->partner = $partner;
    }

    /**
     * @return mixed
     */
    public function getPartner () {
        return $this->partner;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone ($phone) {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone () {
        return $this->phone;
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

}
