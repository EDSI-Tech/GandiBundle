<?php
/**
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 *
 * @author      Philippe BONVIN <p.bonvin@edsi-tech.com>
 * @version     1.0
 * @since       2015-08-18
 */
    
namespace EdsiTech\GandiBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @Assert\Callback(methods={"validateCompanyType", "validateCreation"})
 */
class Contact {
    
    /**
     * @Assert\Null(groups={"create"})
     */
    protected $id;
    
    /**
     * @Assert\NotBlank(groups={"company"})
     */
    protected $company;
    
    protected $vat_number;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $firstname;
    
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $lastname;
    
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $street;
    
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $zip;
    
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $city;
    
    
    /**
     * @Assert\Country()
     * @Assert\Type(type="string")
     */
    protected $country;
        
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $phone;
    
    
    protected $mobile;
    
    protected $fax;
    
    /**
     * @Assert\Language
     */
    
    protected $language;
    
    /**
     * @Assert\Type(type="bool")
     */
    protected $hide_address;
    
    /**
     * @Assert\Type(type="bool")
     */

    protected $hide_email;
    
    /**
     * @Assert\Choice(choices = {Contact::TYPE_PERSON, Contact::TYPE_COMPANY, Contact::TYPE_ASSOCIATION, Contact::TYPE_PUBLICBODY})
     */
    protected $type;
    
    /**
     * @Assert\NotBlank(groups={"create"})
     */
    protected $password;
    
    /**
     * @Assert\Null(groups={"create"})
     */
    protected $handle;
    
    protected $extra_parameters;
    
    
    const TYPE_PERSON = 0;
    const TYPE_COMPANY = 1;
    const TYPE_ASSOCIATION = 2;
    const TYPE_PUBLICBODY = 3;
    const TYPE_RESELLER = 4;

    /**
     * @return array
     */
    public static function getTypes() {
        
        return array(
            self::TYPE_PERSON => 'contact.type.person',
            self::TYPE_COMPANY => 'contact.type.company',
            self::TYPE_ASSOCIATION => 'contact.type.association',
            self::TYPE_PUBLICBODY => 'contact.type.publicbody',
        );
    }

    /**
     * @return array
     */
    public function getExtraParametersTypes() {
        
        return array(
            'birth_city',
            'birth_country',
            'birth_date',
            'birth_department',
            'brand_number',
            'duns',
            'waldec',
            'x-aero_ens_authid',
            'x-aero_ens_authkey',
            'x-au_registrant_id_number',
            'x-travel_uin'
            //...
        );
        
    }

    /**
     * @param null|string $handle
     */
    public function __construct($handle = null) {
        
        $this
            ->setHandle($handle)
            ->setHideAddress(false)
            ->setHideEmail(true)
        ;
        
    }

    /**
     * @return string
     */
    public function __toString() {
        
        if($this->getCompany()) {
            return $this->getCompany().' ('.$this->getHandle().')';
        } else {
            return $this->getFirstName().' '.$this->getLastName().' ('.$this->getHandle().')';
        }
    }

    /**
     * @return array
     */
    public function toGandiArray() {
        
        return array(
            'orgname' => $this->getCompany(),
            'type' => $this->getType(),
            'vat_number' => $this->getVatNumber(),
            'firstname' => $this->getFirstName(),
            'lastname' => $this->getLastName(),
            'streetaddr' => $this->getStreet(),
            'zip' => $this->getZip(),
            'city' => $this->getCity(),
            'country' => $this->getCountry(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'mobile' => $this->getMobile(),
            'fax' => $this->getFax(),
            'lang' => $this->getLanguage(),
            'data_obfuscated' => $this->getHideAddress(),
            'mail_obfuscated' => $this->getHideEmail(),
            'password' => $this->getPassword(),
            'extra_parameters' => $this->getExtraParameters()
        );
        
    }

    /**
     * @param ExecutionContext $ec
     */
    public function validateCompanyType(ExecutionContext $ec)
    {
        if (self::TYPE_PERSON !== $this->getType()) 
        {
          $ec->getGraphWalker()->walkReference($this, 'company', $ec->getPropertyPath(), true);
        }
    }

    /**
     * @param ExecutionContext $ec
     */
    public function validateCreation(ExecutionContext $ec)
    {
        if (null === $this->getHandle()) 
        {
          $ec->getGraphWalker()->walkReference($this, 'create', $ec->getPropertyPath(), true);
        }
    }

    /**
     * @return bool
     */
    public function isNew() {
        
        if($this->getHandle()) {
            return false;
        } else {
            return true;
        }
        
    }

    /**
     * @return string
     */
    public function getCompany() {
        
        return $this->company;
    }

    /**
     * @param string $company
     * @return $this
     */
    public function setCompany($company) {
        
        $this->company = $company;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getType() {
        
        return $this->type;
    }

    /**
     * @param int $type
     * @return $this
     */
    public function setType($type) {
        
        $this->type = $type;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getVATNumber() {
        
        return $this->vat_number;
    }

    /**
     * @param string $vat_number
     * @return $this
     */
    public function setVATNumber($vat_number) {
        
        $this->vat_number = $vat_number;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName() {
        
        return $this->given;
    }

    /**
     * @param string $given
     * @return $this
     */
    public function setFirstName($given) {
        
        $this->given = $given;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName() {
        
        return $this->family;
    }

    /**
     * @param string $family
     * @return $this
     */
    public function setLastName($family) {
        
        $this->family = $family;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet() {
        
        return $this->street;
    }

    /**
     * @param string $street
     * @return $this
     */
    public function setStreet($street) {
        
        $this->street = $street;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getCity() {
        
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city) {
        
        $this->city = $city;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getZip() {
        
        return $this->zip;
    }

    /**
     * @param string $zip
     * @return $this
     */
    public function setZip($zip) {
        
        $this->zip = $zip;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry() {
        
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country) {
        
        $this->country = $country;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() {
        
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email) {
        
        $this->email = $email;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone() {
        
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone) {
        
        $this->phone = $phone;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getMobile() {
        
        return $this->mobile;
    }

    /**
     * @param string $mobile
     * @return $this
     */
    public function setMobile($mobile) {
        
        $this->mobile = $mobile;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getFax() {
        
        return $this->fax;
    }

    /**
     * @param string $fax
     * @return $this
     */
    public function setFax($fax) {
        
        $this->fax = $fax;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage() {
        
        return $this->language;
    }

    /**
     * @param string $language
     * @return $this
     */
    public function setLanguage($language) {
        
        $this->language = $language;
        
        return $this;
    }

    /**
     * @return bool
     */
    public function getHideAddress() {
        
        return $this->hide_address;
    }

    /**
     * @param bool $hide_address
     * @return $this
     */
    public function setHideAddress($hide_address) {
        
        $this->hide_address = $hide_address;
        
        return $this;
    }

    /**
     * @return bool
     */
    public function getHideEmail() {
        
        return $this->hide_email;
    }

    /**
     * @param bool $hide_email
     * @return $this
     */
    public function setHideEmail($hide_email) {
        
        $this->hide_email = $hide_email;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword() {
        
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password) {
        
        $this->password = $password;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getHandle() {
        
        return $this->handle;
    }

    /**
     * @param string $handle
     * @return $this
     */
    public function setHandle($handle) {
        
        $this->handle = $handle;
        
        return $this;
    }

    /**
     * @return string
     */
    public function getId() {
        
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id) {
        
        $this->id = $id;
        
        return $this;
    }

    /**
     * @return array
     */
    public function getExtraParameters() {
        
        return $this->extra_parameters;
    }

    /**
     * @param array $extra_parameters
     * @return $this
     */
    public function setExtraParameters(array $extra_parameters) {
        
        $this->extra_parameters = $extra_parameters;
        
        return $this;
    }

    /**
     * @param $parameter
     * @return $this
     */
    public function addExtraParameter($parameter) {
        
        $this->extra_parameters[] = $parameter;
        
        return $this;
        
    }
    
}