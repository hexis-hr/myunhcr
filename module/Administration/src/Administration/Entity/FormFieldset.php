<?php

namespace Administration\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * FormFieldset
 *
 * @ORM\Table(name="form_fieldset")
 * @ORM\Entity
 */
class FormFieldset {

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
    protected $name = "";

    /**
     * @ORM\Column(type="string")
     */
    protected $fieldsetName = "";

    /**
     * @ORM\ManyToOne(targetEntity="Administration\Entity\Form")
     * @ORM\JoinColumn(name="form", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $form;

    /**
     * @param mixed $form
     */
    public function setForm ($form) {
        $this->form = $form;
    }

    /**
     * @return mixed
     */
    public function getFrom () {
        return $this->form;
    }

    /**
     * @param mixed $fieldsetName
     */
    public function setFieldsetName ($fieldsetName) {
        $this->fieldsetName = $fieldsetName;
    }

    /**
     * @return mixed
     */
    public function getFieldsetName () {
        return $this->fieldsetName;
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

}
