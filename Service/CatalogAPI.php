<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\Service;

use EdsiTech\GandiBundle\Exception\APIException;
use EdsiTech\GandiBundle\Model\Domain;

use Zend\XmlRpc\Client;

class CatalogAPI {
        
    protected $api_key;
    
    protected $gandi;
    
    public function __construct($server_url, $api_key) {
        
        $this->api_key = $api_key;
                
        $this->gandi = new Client($server_url);
    }
    
    /**
     * @param Domain $domain
     * @param String $action = create, renew, transfer, change_owner
     * @param String $grid = A, B, C, D, E
     * @return int price
     */
    public function getPriceForDomain(Domain $domain, $action = 'create', $grid = null) {
        
        $gandi = $this->gandi->getProxy('catalog');
        
        if(null === $grid) {
            $grid = $this->getCurrentGrid();
        }
        
        $options = array(
            'product' => array(
                'type' => 'domain'
            ),
            'action' => array(
                'name' => $action,
            )
        );
        
        $result = $gandi->list($this->api_key, $options, null, $grid);
        
        
        return $result;
        
    }   
    
    public function getAccountBalance() {
        
        $gandi = $this->gandi->getProxy('contact.balance');
        
        $result = $gandi->balance($this->api_key);
        
        return $result['prepaid']['amount'];
        
    }
    
    private function getCurrentGrid() {
        
        $gandi = $gandi = $this->gandi->getProxy('contact');
        
        $result = $gandi->balance($this->api_key);
        
        return $result['grid'];
    }
}