<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\Service;

use EdsiTech\GandiBundle\Model\Contact;
use EdsiTech\GandiBundle\Exception\APIException;

use Zend\XmlRpc\Client;

class ContactAPI {
        
    protected $api_key;
    
    protected $gandi;
    
    protected $validator;
    
    public function __construct($server_url, $api_key, $validator) {
        
        $this->api_key = $api_key;
        $this->validator = $validator;
                
        $this->gandi = new Client($server_url);
        $this->gandi = $this->gandi->getProxy('contact');
    }
    
    
    public function getList() {

        $response = $this->gandi->list($this->api_key);
        
        $data = array();
        
        foreach($response as $current) {
            
            $contact = new Contact();
            $contact->setId($current['id'])
                    ->setLastName($current['family'])
                    ->setHandle($current['handle'])
                    ->setCountry($current['country']);
                    
            $data[] = $contact;
            
        }
        print_r($response);
     
        return $data;
    }
    
    public function get($handle) {
        
        $data = $this->gandi->list($this->api_key, $handle);
        
        if(!is_array($data)) {
            
            throw new \Exception("Cannot get contact details.");
        }
        
        $contact = new Contact();
        $contact->setId($data['id']);
        $contact->setEmail($data['email']);
        $contact->setPhone($data['phone']);
        $contact->setCountry($data['country']);
        
        return $contact;
    }
    
    public function persist(Contact $contact) {
        
        $errors = $this->validator->validate($contact);
        
        if(count($errors) > 0) {
            throw new \Exception(print_r($errors,true));
        }
        
        $data = $contact->toGandiArray();
        
        if($contact->isNew()) {
            
            $response = $this->gandi->create($this->api_key, $data);
            
            if(is_array($response)) {
                return $response['handle'];
            } else {
                
                throw new APIException("Cannot create contact.");
            }
        } else {
            
            $handle = $contact->getHandle();
        
            $response = $this->gandi->update($this->api_key, $handle, $data);
            
            if(is_array($response)) {
                
                return true;
                
            } else {
                
                throw new \Exception("Cannot update contact.");
            }
            
        }
        
    }

    
}