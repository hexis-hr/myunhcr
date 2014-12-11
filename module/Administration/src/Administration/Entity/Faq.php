<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Faq
 *
 * @ORM\Table(name="faq")
 * @ORM\Entity
 */
class Faq {

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
     * @ORM\Column(type="string")
     */
    protected $language = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\CodeCountries")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\FaqCategory")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category;

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

}
