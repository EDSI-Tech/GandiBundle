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
    
    public function __construct($server_url, $api_key) {
        
        $this->api_key = $api_key;

        $this->gandi = new Client($server_url);
        $this->gandi = $this->gandi->getProxy('contact');
    }
    
    
    public function getList() {

        $response = $this->gandi->list($this->api_key);
        
        $data = array();
        
        foreach($response as $current) {
            
            $contact = new Contact($current['handle']);
            $contact->setId($current['id'])
                    ->setCompany($current['orgname'])
                    ->setType($current['type'])
                    ->setVatNumber($current['vat_number'])
                    ->setFirstName($current['given'])
                    ->setLastName($current['family'])
                    ->setStreet($current['streetaddr'])
                    ->setZip($current['zip'])
                    ->setCity($current['city'])
                    ->setCountry($current['country'])
                    ->setEmail($current['email'])
                    ->setPhone($current['phone'])
                    ->setMobile($current['mobile'])
                    ->setFax($current['fax'])
                    ->setLanguage($current['lang'])
                    ->setHideAddress($current['data_obfuscated'])
                    ->setHideEmail($current['mail_obfuscated'])
            ;
            
            
                    
            $data[] = $contact;
            
        }
     
        return $data;
    }
    
    public function get($handle) {
        
        $data = $this->gandi->list($this->api_key, $handle);
        
        if(!is_array($data)) {
            
            throw new \Exception("Cannot get contact details.");
        }
        
        $contact = new Contact();
        $contact->setId($data['id'])
                ->setCompany($data['orgname'])
                ->setType($data['type'])
                ->setVatNumber($data['vat_number'])
                ->setFirstName($data['given'])
                ->setLastName($data['family'])
                ->setStreet($data['streetaddr'])
                ->setZip($data['zip'])
                ->setCity($data['city'])
                ->setCountry($data['country'])
                ->setEmail($data['email'])
                ->setPhone($data['phone'])
                ->setMobile($data['mobile'])
                ->setFax($data['fax'])
                ->setLanguage($data['lang'])
                ->setHideAddress($data['data_obfuscated'])
                ->setHideEmail($data['mail_obfuscated'])
            ;
        
        return $contact;
    }
    
    public function delete(Contact $contact) {
        
        $response = $this->gandi->delete($this->api_key, $contact->getHandle());
        
        if($response) {
            return $response;
        } else {
            throw new APIException("Cannot delete contact.");
        }
        
    }
    
    public function persist(Contact $contact) {
        
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