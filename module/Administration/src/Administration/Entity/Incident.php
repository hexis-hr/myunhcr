<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Incident
 *
 * @ORM\Table(name="incident")
 * @ORM\Entity
 */
class Incident {

    /**
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\SequenceGenerator(sequenceName="id", allocationSize=1, initialValue=1)
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $anonymous = "";

    /**
     * @ORM\Column(type="text")
     */
    protected $description = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $language = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $address = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $latitude = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $longitude = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Country")
     * @ORM\JoinColumn(name="country", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $country;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\IncidentCategory")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\IncidentType")
     * @ORM\JoinColumn(name="type", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $type;


    /**
     * @ORM\ManyToMany(targetEntity="Administration\Entity\File")
     * @ORM\JoinTable(name="incident_file",
     *      joinColumns={@ORM\JoinColumn(name="incident_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="file_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $files;

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $anonymous
     */
    public function setAnonymous($anonymous)
    {
        $anonymous = (boolean) $anonymous;
        $this->anonymous = $anonymous;
    }

    /**
     * @return mixed
     */
    public function getAnonymous()
    {
        return $this->anonymous;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return mixed
     */
    public function getFeaturedFile ()
    {
        return $this->files->first();
    }

    /**
     * @param $file
     */
    public function addFile (File $file) {
        $this->files->add($file);
    }

    /**
     * @param $file
     */
    public function removeFile (File $file) {
        $this->files->removeElement($file);
    }


}
