<?php

class Authors {

    private $nom;
    private $dob;
    private $sex;

    /**
     * Authors constructor.
     * @param $nom
     * @param $dob
     * @param $sex
     */
    public function __construct($nom, $dob, $sex) {
        $this->nom = $nom;
        $this->dob = $dob;
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom) {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getDob() {
        return $this->dob;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob) {
        $this->dob = $dob;
    }

    /**
     * @return mixed
     */
    public function getSex() {
        return $this->sex;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex) {
        $this->sex = $sex;
    }
}