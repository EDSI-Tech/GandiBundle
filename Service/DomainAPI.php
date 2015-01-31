<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\Service;

use EdsiTech\GandiBundle\Model\Contact;
use EdsiTech\GandiBundle\Model\Domain;
use Zend\XmlRpc\Client;
use EdsiTech\GandiBundle\Exception\APIException;

class DomainAPI
{
    protected $api_key;
    
    protected $gandi;

    protected $default_nameservers;
    
    protected $default_handles;
    
    const MAX_TIMEOUT = 5;
    
    public function __construct($server_url, $api_key, $default_nameservers, $default_handles, $contactAPI) {
        
        $this->api_key = $api_key;
        $this->default_nameservers = $default_nameservers;
        $this->default_handles = $default_handles;
        
        $this->gandi = new Client($server_url);
    }

    /**
     * Get domain names list
     *
     * @param array $options
     * @return array
     */
    public function getList(array $options = null)
    {
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

    /**
     * @param Domain $domain
     * @return mixed
     * @throws APIException
     * @throws \Exception
     */
    public function register(Domain $domain)
    {
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
            
            throw new \APIException("This contact cannot register this domain.");
        
        }
   
        //if no DNS servers defined, add servers from the configuration file
        if((count($domain->getNameservers()) < 1) && (count($this->default_nameservers) > 0)) {

            foreach($this->default_nameservers as $ns) {
                $domain->addNameserver($ns);
            }
            
        }
        
        //add default admin handle if not set
        if(null == $domain->getAdminContact() && array_key_exists('admin', $this->default_handles)) {
            $domain->setAdminContact(new Contact($this->default_handles['admin']));
        }
        
        //add default bill handle if not set
        if(null == $domain->getBillContact() && array_key_exists('bill', $this->default_handles)) {
            $domain->setBillContact(new Contact($this->default_handles['bill']));
        }
        
        //add default tech handle if not set
        if(null == $domain->getTechContact() && array_key_exists('tech', $this->default_handles)) {
            $domain->setTechContact(new Contact($this->default_handles['tech']));
        }
        
        //add default owner handle if not set
        if(null == $domain->getOwnerContact() && array_key_exists('owner', $this->default_handles)) {
            $domain->setOwnerContact(new Contact($this->default_handles['owner']));
        }
        
        $data = $domain->toGandiArray();
        
        $result = $gandi->create($this->api_key, $fdqn, $data);
        
        if($result['last_error']) {
            throw new APIException($result['last_error']);
        }
        
        $operation_id = $result['id'];
        
        return $operation_id;
        
    }

    /**
     * @param $domainName
     * @return array
     */
    public function getInfo(Domain $domain)
    {
        $gandi = $this->gandi->getProxy('domain');
        
        return $gandi->info($this->api_key, $domain->getFqdn());
    }

    /**
     * @param Domain $domain
     * @return bool
     */
    public function enableAutorenew(Domain $domain) {
        
        $gandi = $this->gandi->getProxy('domain.autorenew');

        $result = $gandi->activate($this->api_key, $domain->getFqdn());

        if(true == $result['active']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Domain $domain
     * @return bool
     */
    public function disableAutorenew(Domain $domain) {
        
        $gandi = $this->gandi->getProxy('domain.autorenew');
        
        $result = $gandi->deactivate($this->api_key, $domain->getFqdn());
            
        if(false == $result['active']) {
            return true;
        } else {
            return false;
        }
    }
}