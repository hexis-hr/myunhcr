<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceActivity
 *
 * @ORM\Table(name="service_activity")
 * @ORM\Entity
 */
class ServiceActivity {

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
    protected $activityName = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $activityCategory = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $activityStart = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $activityEnd = "";

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
     * @param mixed $activityCategory
     */
    public function setActivityCategory ($activityCategory) {
        $this->activityCategory = $activityCategory;
    }

    /**
     * @return mixed
     */
    public function getActivityCategory () {
        return $this->activityCategory;
    }

    /**
     * @param mixed $activityEnd
     */
    public function setActivityEnd ($activityEnd) {
        $this->activityEnd = $activityEnd;
    }

    /**
     * @return mixed
     */
    public function getActivityEnd () {
        return $this->activityEnd;
    }

    /**
     * @param mixed $activityName
     */
    public function setActivityName ($activityName) {
        $this->activityName = $activityName;
    }

    /**
     * @return mixed
     */
    public function getActivityName () {
        return $this->activityName;
    }

    /**
     * @param mixed $activityStart
     */
    public function setActivityStart ($activityStart) {
        $this->activityStart = $activityStart;
    }

    /**
     * @return mixed
     */
    public function getActivityStart () {
        return $this->activityStart;
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
