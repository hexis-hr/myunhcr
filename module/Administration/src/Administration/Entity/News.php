<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity
 */
class News {

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
    protected $title = "";

    /**
     * @ORM\Column(type="text")
     */
    protected $content = "";

    /**
     * @ORM\Column(type="date")
     */
    protected $date = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $language = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * @param mixed $language
     */
    public function setLanguage ($language) {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage () {
        return $this->language;
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
     * @param mixed $title
     */
    public function setTitle ($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle () {
        return $this->title;
    }

    public function __construct() {
        $this->date = new \DateTime('now');
    }
}
