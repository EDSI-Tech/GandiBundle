<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\Service;

use EdsiTech\GandiBundle\Model\Contact;
use EdsiTech\GandiBundle\Model\Domain;

use Zend\XmlRpc\Client;

class DomainAPI {
    
    protected $api_key;
    
    protected $gandi;
    
    protected $validator;
    
    protected $default_nameservers;
    
    const MAX_TIMEOUT = 5;
    
    public function __construct($server_url, $api_key, $default_nameservers, $contactAPI, $validator) {
        
        $this->api_key = $api_key;
        $this->validator = $validator;
        $this->default_nameservers = $default_nameservers;
        
        $this->gandi = new Client($server_url);
    }
    
    
    /**
      * @TODO : mettre en cache les extensions
      */
    public function getExtensions() {

        $gandi = $this->gandi->getProxy('domain.tld');
         
        $extensions = $gandi->list($this->api_key);  
        
        $data = array();

        foreach($extensions as $ext) {
            
            if('golive' == $ext['phase'] && 'all' == $ext['visibility']) {
                $data[] = $ext['name'];
            }
            
        }

        return $data;
    }
    
    public function getList(array $options = null) {
        
        $gandi = $this->gandi->getProxy('domain');
        
        $result = $gandi->list($this->api_key, $options);
        
        $data = array();
        
        foreach($result as $current) {
            
            $domain = new Domain($current['fqdn']);
            $domain->setId($current['id']);
            
            $data[] = $domain;
        }
        
        return $data;
    }
    
    
    public function isAvailable(array $domains, array $options = null) {
        
        $maxRetry = 0;
        
        $domain_string = array();
        
        foreach ($domains as $domain) {
            $domain_string[] = $domain->getFqdn();
        }
        
        $gandi = $this->gandi->getProxy('domain');
        
        $results = $gandi->available($this->api_key, $domain_string, $options);
        
        foreach($results as $domain => $result) {
            
            if('pending' == $result) {
                $maxRetry++;
                
                //if max retry has expired, return the data as it.
                if($maxRetry > self::MAX_TIMEOUT) {
                    return $results;
                }
                
                sleep(1);
                return $this->isAvailable($domains);
            }
        }
        
        return $results;
        
    }
    
    public function register(Domain $domain) {
        //faire valider le domaine avec le validator
        
        $errors = $this->validator->validate($domain);
        
        if(count($errors) > 0) {
            throw new \Exception(print_r($errors,true));
        }
        
        $fdqn = $domain->getFqdn();
        
        $gandi = $this->gandi->getProxy('domain');

        //test if the owner can register the domain
        if(!$this->gandi->getProxy('contact')->can_associate_domain(
                                    $this->api_key, 
                                    $domain->getOwnerContact()->getHandle(),
                                    array(
                                        'domain' => $fdqn,
                                    ))
        ) {
            
            throw new \Exception("This contact cannot register this domain.");
        
        }
        
        //if no DNS servers defined, add servers from the configuration file
        if((count($domain->getNameservers()) < 1) && (count($this->default_nameservers) > 0)) {

            foreach($this->default_nameservers as $ns) {
                $domain->addNameserver($ns);
            }
            
        }
        
        $data = $domain->toGandiArray();
        $result = $gandi->create($this->api_key, $fdqn, $data);
        
        if($result['last_error']) {
            throw new APIException($result['last_error']);
        }
        
        $operation_id = $result['id'];
        
        return $operation_id;
        
    }
    
    public function getDomain(Domain $domain) {
        
        
    }
    
    public function update(Domain $domain) {
        
        
    }
    
}