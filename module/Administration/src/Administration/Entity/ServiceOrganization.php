<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceOrganization
 *
 * @ORM\Table(name="service_organization")
 * @ORM\Entity
 */
class ServiceOrganization {

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
    protected $organizationName = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $organizationAcronym = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $organizationUrl = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $organizationAddress = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $organizationEmail = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $organizationPhone = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $organizationOpeningHours = "";

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
     * @param mixed $organizationAcronym
     */
    public function setOrganizationAcronym ($organizationAcronym) {
        $this->organizationAcronym = $organizationAcronym;
    }

    /**
     * @return mixed
     */
    public function getOrganizationAcronym () {
        return $this->organizationAcronym;
    }

    /**
     * @param mixed $organizationEmail
     */
    public function setOrganizationEmail ($organizationEmail) {
        $this->organizationEmail = $organizationEmail;
    }

    /**
     * @return mixed
     */
    public function getOrganizationEmail () {
        return $this->organizationEmail;
    }

    /**
     * @param mixed $organizationOpeningHours
     */
    public function setOrganizationOpeningHours ($organizationOpeningHours) {
        $this->organizationOpeningHours = $organizationOpeningHours;
    }

    /**
     * @return mixed
     */
    public function getOrganizationOpeningHours () {
        return $this->organizationOpeningHours;
    }

    /**
     * @param mixed $organizationPhone
     */
    public function setOrganizationPhone ($organizationPhone) {
        $this->organizationPhone = $organizationPhone;
    }

    /**
     * @return mixed
     */
    public function getOrganizationPhone () {
        return $this->organizationPhone;
    }

    /**
     * @param mixed $organizationName
     */
    public function setOrganizationName ($organizationName) {
        $this->organizationName = $organizationName;
    }

    /**
     * @return mixed
     */
    public function getOrganizationName () {
        return $this->organizationName;
    }

    /**
     * @param mixed $organizationUrl
     */
    public function setOrganizationUrl ($organizationUrl) {
        $this->organizationUrl = $organizationUrl;
    }

    /**
     * @return mixed
     */
    public function getOrganizationUrl () {
        return $this->organizationUrl;
    }

    /**
     * @param mixed $organizationAddress
     */
    public function setOrganizationAddress ($organizationAddress) {
        $this->organizationAddress = $organizationAddress;
    }

    /**
     * @return mixed
     */
    public function getOrganizationAddress () {
        return $this->organizationAddress;
    }

}
