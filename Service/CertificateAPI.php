<?php
/**
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 *
 * @author      Philippe BONVIN <p.bonvin@edsi-tech.com>
 * @version     1.0
 * @since       2015-08-23
 */

namespace EdsiTech\GandiBundle\Service;

use EdsiTech\GandiBundle\Model\Certificate;
use EdsiTech\GandiBundle\Exception\APIException;

use Zend\XmlRpc\Client;

class CertificateAPI {

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
        $this->gandi = $this->gandi->getProxy('cert');
    }

    /**
     * @param Certificate $certificate
     * @return Operation
     * @throws APIException
     */
    public function create(Certificate $certificate) {

        $result = $this->gandi->create($this->api_key, array(
            'csr' => $certificate->getCsr(),
            'duration' => $certificate->getDuration(),
            'handle' => $certificate->getOwner()->getHandle(),
            'dcv_method' => $certificate->getValidationMethod(),
            'package' => $certificate->getType(),
        ));
        
        if($result['last_error']) {
            throw new APIException($result['last_error']);
        }
        
        return new Operation($result);
     
    }

    /**
     * @param Certificate $certificate
     * @return Operation
     * @throws APIException
     */
    public function renew(Certificate $certificate) {

        $result = $this->gandi->renew($this->api_key, array(
            'csr' => $certificate->getCsr(),
            'duration' => $certificate->getDuration(),
            'dcv_method' => $certificate->getValidationMethod(),
        ));
        
        if($result['last_error']) {
            throw new APIException($result['last_error']);
        }
        
        return new Operation($result);
     
    }

    /**
     * @param $fqdn
     * @return mixed
     */
    public function get($fqdn) {
        
        return $this->gandi->list($this->api_key, array(
            'cn' => $fqdn,
        ));
        
        
    }

    /**
     * @param array|null $options
     * @return mixed
     */
    public function list(array $options = null) {

        $result = $this->gandi->list($this->api_key, $options);

        return $result;
     
    }

    
}