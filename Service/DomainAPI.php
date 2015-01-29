<?php
    
namespace EdsiTech\GandiBundle\Service;

require_once 'XML_RPC2/Client.php';

class DomainAPI {
    
    protected $server_url;
    
    protected $api_key;
    
    public function __construct($server_url, $api_key) {
        
        $this->server_url = $server_url;
        
        $this->api_key = $api_key;
                
    }
    
    public function getExtensions() {
        
        $gandi = XML_RPC2_Client::create(
            $this->server_url,
            array( 'prefix' => 'domain.tld.')
        );
        
        return $gandi->list($this->api_key);
    }
    
}