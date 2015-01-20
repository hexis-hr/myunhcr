<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\DateTime;


/**
 * SurveyResult
 *
 * @ORM\Table(name="survey_result")
 * @ORM\Entity
 */
class SurveyResult {

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
    protected $fieldName = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $fieldValue = "";

    /**
     * @ORM\Column(type="integer")
     */
    protected $authId = "";

    /**
     * @ORM\Column(type="datetime")
     */
    protected $birthDate;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Form")
     * @ORM\JoinColumn(name="form", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $form = "";

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
     * @param mixed $fieldName
     */
    public function setFieldName ($fieldName) {
        $this->fieldName = $fieldName;
    }

    /**
     * @return mixed
     */
    public function getFieldName () {
        return $this->fieldName;
    }

    /**
     * @param mixed $fieldValue
     */
    public function setFieldValue ($fieldValue) {
        $this->fieldValue = $fieldValue;
    }

    /**
     * @return mixed
     */
    public function getFieldValue () {
        return $this->fieldValue;
    }

    /**
     * @param mixed $form
     */
    public function setForm ($form) {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    public function getForm () {
        return $this->form;
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
     * @param mixed $birthDate
     */
    public function setBirthDate ($birthDate) {
        $this->birthDate = new \DateTime($birthDate);
    }

    /**
     * @return mixed
     */
    public function getBirthDate () {
        return $this->birthDate;
    }

}
