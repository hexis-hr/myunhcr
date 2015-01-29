<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Complaint
 *
 * @ORM\Table(name="complaint")
 * @ORM\Entity
 */
class Complaint {

    /**
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\SequenceGenerator(sequenceName="id", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $content = "";

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * @param mixed $content
     */
    public function setContent ($content) {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent () {
        return $this->content;
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

}
