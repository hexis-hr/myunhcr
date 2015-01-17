<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * FaqCategory
 *
 * @ORM\Table(name="faq_category")
 * @ORM\Entity
 */
class FaqCategory {

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
    protected $category = "";

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
