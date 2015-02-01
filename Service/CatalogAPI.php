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
    
    /**
     * @param String $server_url
     * @param String $api_key
     */
    public function __construct($server_url, $api_key) {
        
        $this->api_key = $api_key;
                
        $this->gandi = new Client($server_url);
    }
    
    /**
     * @param Domain $domain
     * @param String $action = create, renew, transfer, change_owner
     * @param String $grid = A, B, C, D, E
     * @return float price
     */
    public function getPriceForDomain(Domain $domain, $action = 'create', $currency = 'EUR', $grid = null) {
        
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
        
        $result = $gandi->list($this->api_key, $options, $currency, $grid);
        
        foreach($result as $price) {
            
            if($domain->getTld() == $price['product']['description']) {
                
                return $price['unit_price'][0]['price'];
                
            }
            
        }
        
        return null;
        
    }   
    
    /**
     * @return float price
     */
    public function getAccountBalance() {
        
        $gandi = $this->gandi->getProxy('contact');
        
        $result = $gandi->balance($this->api_key);
        
        return $result['prepaid']['amount'];
        
    }
    
    /**
     * @return String
     */
    private function getCurrentGrid() {
        
        $gandi = $gandi = $this->gandi->getProxy('contact');
        
        $result = $gandi->balance($this->api_key);
        
        return $result['grid'];
    }
}