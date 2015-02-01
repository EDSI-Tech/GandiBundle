<?php
        
/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */
    
namespace EdsiTech\GandiBundle\Model;

class Operation {

    protected $id;
    
    protected $created;

    protected $updated;
    
    protected $error;
    
    protected $type;
    
    protected $step;
    
    public function __construct($data) {
        
        $this->id = $data['id'];
        $this->created = new \DateTime($data['date_created']);
        $this->updated = new \DateTime($data['date_updated']);
        $this->error = $data['last_error'];
        $this->type = $data['type'];
        $this->step = $data['step'];
        
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
     * @return String
     */

    public function getCreated() {

        return $this->created;
    }

    /**
     * @param String $created
     * @return $this
     */
    public function setCreated(\DateTime $created) {

        $this->created = $created;

        return $this;
    }
    
    /**
     * @return String
     */

    public function getUpdated() {

        return $this->updated;
    }

    /**
     * @param DateTime $updated
     * @return $this
     */
    public function setUpdated(\DateTime $updated) {

        $this->updated = $updated;

        return $this;
    }
    
    public function getType() {
        
        return $this->type;
    }
    
    public function setType($type) {
        
        $this->type = $type;
        
        return $this;
    }
    
    public function getStep() {
        
        return $this->step;
    }
    
    public function setStep($step) {
        
        $this->step = $step;
        
        return $this;
    }
    
}