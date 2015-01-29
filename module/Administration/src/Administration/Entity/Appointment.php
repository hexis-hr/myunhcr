<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Appointment
 *
 * @ORM\Table(name="appointment")
 * @ORM\Entity
 */
class Appointment {

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
    protected $authId = "";

    /**
     * @ORM\Column(type="date")
     */
    protected $date = "";

    /**
     * @ORM\Column(type="time")
     */
    protected $time = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\AppointmentCategory")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category;


    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * @param mixed $authId
     */
    public function setAuthId ($authId) {
        $this->authId = $authId;
    }

    /**
     * @return mixed
     */
    public function getAuthId () {
        return $this->authId;
    }

    /**
     * @param mixed $category
     */
    public function setCategory ($category) {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategory () {
        return $this->category;
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
     * @param mixed $date
     */
    public function setDate ($date) {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate () {
        return $this->date;
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
     * @param mixed $time
     */
    public function setTime ($time) {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getTime () {
        return $this->time;
    }

}
