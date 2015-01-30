<?php
        
/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */
    
namespace EdsiTech\GandiBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Contact {
    
    protected $id;
    
    /**
     * @Assert\NotBlank()
     */
    protected $given;
    
    
    /**
     * @Assert\NotBlank()
     */
    protected $family;
    
    
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
    
    
    /**
     * @Assert\NotBlank()
     */
    protected $street;
    
    
    /**
     * @Assert\NotBlank()
     */
    protected $zip;
    
    
    /**
     * @Assert\NotBlank()
     */
    protected $city;
    
    
    /**
     * @Assert\Country()
     */
    protected $country;
    
    
    /**
     * @Assert\NotBlank()
     */
    protected $phone;
    
    
    /**
     * @Assert\Choice(choices = {Contact::TYPE_PARTICULAR, Contact::TYPE_COMPANY, Contact::TYPE_ASSOCIATION, Contact::TYPE_PUBLICBODY})
     */
    protected $type;
    
    
    
    protected $password;
    
    protected $handle;
    
    
    const TYPE_PARTICULAR = 0;
    const TYPE_COMPANY = 1;
    const TYPE_ASSOCIATION = 2;
    const TYPE_PUBLICBODY = 3;
    
    public function getTypes() {
        
        return array(
            self::TYPE_PARTICULAR,
            self::TYPE_COMPANY,
            self::TYPE_ASSOCIATION,
            self::TYPE_PUBLICBODY
        );
    }
    
    public function __construct($handle = null) {
        
        $this->setHandle($handle);
        
    }
    
    public function __toString() {
        
        return $this->getFirstName().' '.$this->getLastName().' ('.$this->getHandle().')';
    }
    
    public function toGandiArray() {
        
        return array(
            'given' => $this->getFirstName(),
            'family' => $this->getLastName(),
            'streetaddr' => $this->getStreet(),
            'city' => $this->getCity(),
            'zip' => $this->getZip(),
            'country' => $this->getCountry(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'type' => $this->getType(),
            'password' => $this->getPassword()
        );
        
    }
    
    public function isNew() {
        
        if($this->getHandle()) {
            return false;
        } else {
            return true;
        }
        
    }
    
    
    public function getFirstName() {
        
        return $this->given;
    }
    
    public function setFirstName($given) {
        
        $this->given = $given;
        
        return $this;
    }
    
    public function getLastName() {
        
        return $this->family;
    }
    
    public function setLastName($family) {
        
        $this->family = $family;
        
        return $this;
    }
    
    public function getType() {
        
        return $this->type;
    }
    
    public function setType($type) {
        
        $this->type = $type;
        
        return $this;
    }
    
    public function getEmail() {
        
        return $this->email;
    }
    
    public function setEmail($email) {
        
        $this->email = $email;
        
        return $this;
    }
    
    public function getPhone() {
        
        return $this->phone;
    }
    
    public function setPhone($phone) {
        
        $this->phone = $phone;
        
        return $this;
    }
    
    public function getStreet() {
        
        return $this->street;
    }
    
    public function setStreet($street) {
        
        $this->street = $street;
        
        return $this;
    }
    
    public function getCity() {
        
        return $this->city;
    }
    
    public function setCity($city) {
        
        $this->city = $city;
        
        return $this;
    }
    
    public function getZip() {
        
        return $this->zip;
    }
    
    public function setZip($zip) {
        
        $this->zip = $zip;
        
        return $this;
    }
    
    public function getCountry() {
        
        return $this->country;
    }
    
    public function setCountry($country) {
        
        $this->country = $country;
        
        return $this;
    }
    
    public function getPassword() {
        
        return $this->password;
    }
    
    public function setPassword($password) {
        
        $this->password = $password;
        
        return $this;
    }
    
    public function getHandle() {
        
        return $this->handle;
    }
    
    public function setHandle($handle) {
        
        $this->handle = $handle;
        
        return $this;
    }
    
    public function getId() {
        
        return $this->id;
    }
    
    public function setId($id) {
        
        $this->id = $id;
        
        return $this;
    }
    
}