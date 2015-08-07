<?php
/**
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 *
 * @author      Philippe BONVIN <p.bonvin@edsi-tech.com>
 * @version     1.0
 * @since       2015-08-21
 */

namespace EdsiTech\GandiBundle\Service;

use EdsiTech\GandiBundle\Model\Contact;
use EdsiTech\GandiBundle\Exception\APIException;

use Zend\XmlRpc\Client;

class ContactAPI {

    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var Client\ServerProxy
     */
    protected $gandi;

    /**
     * @param $server_url
     * @param $api_key
     */
    public function __construct($server_url, $api_key) {
        
        $this->api_key = $api_key;

        $this->gandi = new Client($server_url);
        $this->gandi = $this->gandi->getProxy('contact');
    }

    /**
     * @return array
     */
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

    /**
     * @param $handle
     * @return mixed
     * @throws \Exception
     */
    public function get($handle) {
        
        $data = $this->gandi->info($this->api_key, $handle);
        
        if(!is_array($data)) {
            
            throw new \Exception("Cannot get contact details.");
        }

        return $data;
    }

    /**
     * @param Contact $contact
     * @return mixed
     * @throws APIException
     */
    public function delete(Contact $contact) {
        
        $response = $this->gandi->delete($this->api_key, $contact->getHandle());
        
        if($response) {
            return $response;
        } else {
            throw new APIException("Cannot delete contact.");
        }
        
    }

    /**
     * @param Contact $contact
     * @return bool
     * @throws APIException
     * @throws \Exception
     */
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