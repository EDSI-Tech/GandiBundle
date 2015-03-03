<?php
        
/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */
    
namespace EdsiTech\GandiBundle\Model;

class Certificate {

    protected $id;
    
    protected $owner;
    
    protected $created;
    
    protected $expire;
    
    protected $duration;
    
    protected $validation_method;
    
    protected $crt;
    
    protected $csr;
    
    static TYPE_STANDARD = 'cert_std_1_0_0';
    static TYPE_STANDARD_WILDCARD = 'cert_std_w_0_0';
    
    static METHOD_EMAIL = 'email';
    static METHOD_DNS = 'dns';
    static METHOD_FILE = 'file';
    
    public function __construct($data) {
        

        
    }
    
    public function __toString() {
        
        return $this->getId();
    }
    
    public function getId() {
        
        return $this->id;
    }
    
    public function setId($id) {
        
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return DateTime
     */

    public function getCreated() {

        return $this->created;
    }

    /**
     * @param DateTime $created
     * @return $this
     */
    public function setCreated(\DateTime $created) {

        $this->created = $created;

        return $this;
    }
    
    /**
     * @return DateTime
     */

    public function getExpire() {

        return $this->expire;
    }

    /**
     * @param DateTime $updated
     * @return $this
     */
    public function setUpdated(\DateTime $expire) {

        $this->expire = $expire;

        return $this;
    }
    
     
}