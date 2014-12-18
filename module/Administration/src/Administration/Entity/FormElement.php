<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * FormElement
 *
 * @ORM\Table(name="form_element")
 * @ORM\Entity
 */
class FormElement {

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
    protected $label = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $name = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $type = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $mediaType = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $parentType = "";

    /**
     * @ORM\Column(type="integer")
     */
    protected $parentId = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\FormFieldset")
     * @ORM\JoinColumn(name="fieldset", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $fieldset = "";

    /**
     * @ORM\Column(type="text")
     */
    protected $valueOptions = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $ref = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $required = "";

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
     * @param mixed $fieldset
     */
    public function setFieldset ($fieldset) {
        $this->fieldset = $fieldset;
    }

    /**
     * @return mixed
     */
    public function getFieldset () {
        return $this->fieldset;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId ($parentId) {
        $this->parentId = $parentId;
    }

    /**
     * @return mixed
     */
    public function getParentId () {
        return $this->parentId;
    }

    /**
     * @param mixed $valueOptions
     */
    public function setValueOptions ($valueOptions) {
        $this->valueOptions = $valueOptions;
    }

    /**
     * @return mixed
     */
    public function getValueOptions () {
        return $this->valueOptions;
    }

    /**
     * @param mixed $mediaType
     */
    public function setMediaType ($mediaType) {
        $this->mediaType = $mediaType;
    }

    /**
     * @return mixed
     */
    public function getMediaType () {
        return $this->mediaType;
    }

    /**
     * @param mixed $parentType
     */
    public function setParentType ($parentType) {
        $this->parentType = $parentType;
    }

    /**
     * @return mixed
     */
    public function getParentType () {
        return $this->parentType;
    }

    /**
     * @param mixed $required
     */
    public function setRequired ($required) {
        $this->required = $required;
    }

    /**
     * @return mixed
     */
    public function getRequired () {
        return $this->required;
    }

    /**
     * @param mixed $type
     */
    public function setType ($type) {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType () {
        return $this->type;
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
     * @param mixed $label
     */
    public function setLabel ($label) {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getLabel () {
        return $this->label;
    }

    /**
     * @param mixed $ref
     */
    public function setRef ($ref) {
        $this->ref = $ref;
    }

    /**
     * @return mixed
     */
    public function getRef () {
        return $this->ref;
    }

}
