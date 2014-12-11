<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CodeCountries
 *
 * @ORM\Table(name="code_countries")
 * @ORM\Entity
 */
class CodeCountries {
    
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
    protected $code;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $nameLong;

    /**
     * @ORM\Column(type="string")
     */
    protected $nameFormal;

    /**
     * @ORM\Column(type="string")
     */
    protected $code2;

    /**
     * @ORM\Column(type="bigint")
     */
    protected $oracleId;

    /**
     * @ORM\Column(type="string")
     */
    protected $nationality;

    /**
     * @ORM\Column(type="string")
     */
    protected $majorArea;

    /**
     * @ORM\Column(type="string")
     */
    protected $region;

    /**
     * @ORM\Column(type="string")
     */
    protected $lessDeveloped;

    /**
     * @ORM\Column(type="string")
     */
    protected $leastDeveloped;

    /**
     * @ORM\Column(type="integer")
     */
    protected $bureau;

    /**
     * @ORM\Column(type="string")
     */
    protected $bureauOrder;

    /**
     * @ORM\Column(type="string")
     */
    protected $bureauFull;

    /**
     * @ORM\Column(type="string")
     */
    protected $nameFr;

    /**
     * @ORM\Column(type="string")
     */
    protected $majorAreaFr;

    /**
     * @ORM\Column(type="string")
     */
    protected $regionFr;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $active;

    /**
     * @param mixed $active
     */
    public function setActive ($active) {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getActive () {
        return $this->active;
    }

    /**
     * @param mixed $bureau
     */
    public function setBureau ($bureau) {
        $this->bureau = $bureau;
    }

    /**
     * @return mixed
     */
    public function getBureau () {
        return $this->bureau;
    }

    /**
     * @param mixed $bureauFull
     */
    public function setBureauFull ($bureauFull) {
        $this->bureauFull = $bureauFull;
    }

    /**
     * @return mixed
     */
    public function getBureauFull () {
        return $this->bureauFull;
    }

    /**
     * @param mixed $bureauOrder
     */
    public function setBureauOrder ($bureauOrder) {
        $this->bureauOrder = $bureauOrder;
    }

    /**
     * @return mixed
     */
    public function getBureauOrder () {
        return $this->bureauOrder;
    }

    /**
     * @param mixed $code
     */
    public function setCode ($code) {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode () {
        return $this->code;
    }

    /**
     * @param mixed $code2
     */
    public function setCode2 ($code2) {
        $this->code2 = $code2;
    }

    /**
     * @return mixed
     */
    public function getCode2 () {
        return $this->code2;
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
     * @param mixed $leastDeveloped
     */
    public function setLeastDeveloped ($leastDeveloped) {
        $this->leastDeveloped = $leastDeveloped;
    }

    /**
     * @return mixed
     */
    public function getLeastDeveloped () {
        return $this->leastDeveloped;
    }

    /**
     * @param mixed $lessDeveloped
     */
    public function setLessDeveloped ($lessDeveloped) {
        $this->lessDeveloped = $lessDeveloped;
    }

    /**
     * @return mixed
     */
    public function getLessDeveloped () {
        return $this->lessDeveloped;
    }

    /**
     * @param mixed $majorArea
     */
    public function setMajorArea ($majorArea) {
        $this->majorArea = $majorArea;
    }

    /**
     * @return mixed
     */
    public function getMajorArea () {
        return $this->majorArea;
    }

    /**
     * @param mixed $majorAreaFr
     */
    public function setMajorAreaFr ($majorAreaFr) {
        $this->majorAreaFr = $majorAreaFr;
    }

    /**
     * @return mixed
     */
    public function getMajorAreaFr () {
        return $this->majorAreaFr;
    }

    /**
     * @param mixed $name
     */
    public function setName ($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName () {
        return $this->name;
    }

    /**
     * @param mixed $nameFormal
     */
    public function setNameFormal ($nameFormal) {
        $this->nameFormal = $nameFormal;
    }

    /**
     * @return mixed
     */
    public function getNameFormal () {
        return $this->nameFormal;
    }

    /**
     * @param mixed $nameFr
     */
    public function setNameFr ($nameFr) {
        $this->nameFr = $nameFr;
    }

    /**
     * @return mixed
     */
    public function getNameFr () {
        return $this->nameFr;
    }

    /**
     * @param mixed $nameLong
     */
    public function setNameLong ($nameLong) {
        $this->nameLong = $nameLong;
    }

    /**
     * @return mixed
     */
    public function getNameLong () {
        return $this->nameLong;
    }

    /**
     * @param mixed $nationality
     */
    public function setNationality ($nationality) {
        $this->nationality = $nationality;
    }

    /**
     * @return mixed
     */
    public function getNationality () {
        return $this->nationality;
    }

    /**
     * @param mixed $oracleId
     */
    public function setOracleId ($oracleId) {
        $this->oracleId = $oracleId;
    }

    /**
     * @return mixed
     */
    public function getOracleId () {
        return $this->oracleId;
    }

    /**
     * @param mixed $region
     */
    public function setRegion ($region) {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getRegion () {
        return $this->region;
    }

    /**
     * @param mixed $regionFr
     */
    public function setRegionFr ($regionFr) {
        $this->regionFr = $regionFr;
    }

    /**
     * @return mixed
     */
    public function getRegionFr () {
        return $this->regionFr;
    }

    

}
