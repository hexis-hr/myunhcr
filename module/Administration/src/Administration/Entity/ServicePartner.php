<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServicePartner
 *
 * @ORM\Table(name="service_partner")
 * @ORM\Entity
 */
class ServicePartner {

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
    protected $partnerName = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $partnerAcronym = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $partnerUrl = "";

    /**
     * @ORM\Column(type="string")
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
     * @param mixed $partnerAcronym
     */
    public function setPartnerAcronym ($partnerAcronym) {
        $this->partnerAcronym = $partnerAcronym;
    }

    /**
     * @return mixed
     */
    public function getPartnerAcronym () {
        return $this->partnerAcronym;
    }

    /**
     * @param mixed $partnerName
     */
    public function setPartnerName ($partnerName) {
        $this->partnerName = $partnerName;
    }

    /**
     * @return mixed
     */
    public function getPartnerName () {
        return $this->partnerName;
    }

    /**
     * @param mixed $partnerUrl
     */
    public function setPartnerUrl ($partnerUrl) {
        $this->partnerUrl = $partnerUrl;
    }

    /**
     * @return mixed
     */
    public function getPartnerUrl () {
        return $this->partnerUrl;
    }

}
