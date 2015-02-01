<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\Service;

use EdsiTech\GandiBundle\Model\Operation;
use EdsiTech\GandiBundle\Exception\APIException;

use Zend\XmlRpc\Client;

class OperationAPI {
        
    protected $api_key;
    
    protected $gandi;
    
    public function __construct($server_url, $api_key) {
        
        $this->api_key = $api_key;
                
        $this->gandi = new Client($server_url);
        $this->gandi = $this->gandi->getProxy('operation');
    }
    
    public function details(Operation $operation) {
        
        $operation = $this->gandi->info($this->api_key, $operation->getId());
        
        return $operation;
    }
    
    public function getList(array $options = null) {
        
        $result = $this->gandi->info($this->api_key, $options);
        
        $data = array();
        
        foreach($result as $operation) {
            
            $data[] = new Operation($operation);
        }
        
        return $data;
    }
    
    public function cancel(Operation $operation) {
        
        return $this->gandi->cancel($this->api_key, $operation->getId());
    }
    
    public function restart(Operation $operation, array $options = null) {
        
        return $this->gandi->relaunch($this->api_key, $operation->getId(), $options);
    }
    
    
}