<?php
/**
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 *
 * @author      Philippe BONVIN <p.bonvin@edsi-tech.com>
 * @version     1.0
 * @since       2015-08-18
 */

namespace EdsiTech\GandiBundle\Factory;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;
use EdsiTech\GandiBundle\Service\DomainAPI;
use EdsiTech\GandiBundle\Model\Domain;
use EdsiTech\GandiBundle\Model\Contact;

class DomainFactory
{
    /**
     * @var DomainAPI
     */
    private $domainAPI;

    /**
     * @param DomainAPI $domainAPI
     */
    public function __construct(DomainAPI $domainAPI)
    {
        $this->domainAPI = $domainAPI;
    }

    /**
     * @param $domainName
     * @return Domain proxy object
     */
    public function build($domainName)
    {
        
        $factory     = new LazyLoadingValueHolderFactory();
        $initializer = function (&$wrappedObject, LazyLoadingInterface $proxy, $method, array $parameters, & $initializer) use ($domainName) {
            $initializer = null; // disable further initialization
            
            $wrappedObject = new Domain($domainName);
            
            $result = $this->domainAPI->getInfo($wrappedObject);

            $wrappedObject
                ->setAuthInfo($result['authinfo'])
                ->setNameservers($result['nameservers'])
                ->setAutorenew($result['autorenew']['active'])
                ->setTld($result['tld'])
                ->setStatus($result['status'])
                ->setOwnerContact(new Contact($result['contacts']['owner']['handle']))
                ->setAdminContact(new Contact($result['contacts']['admin']['handle']))
                ->setBillContact(new Contact($result['contacts']['bill']['handle']))
                ->setTechContact(new Contact($result['contacts']['tech']['handle']))
                ->setResellerContact(new Contact($result['contacts']['reseller']['handle']))
                ->setCreated(new \DateTime($result['date_registry_creation']))
                ->setUpdated(new \DateTime($result['date_updated']))
                ->setExpire(new \DateTime($result['date_registry_end']))
            ;
            
            $wrappedObject->setLock(false);
            
            foreach($result['status'] as $status) {

                if('clientTransferProhibited' == $status) {
                    $wrappedObject->setLock(true);
                }
                
            }
            
            return true; // confirm that initialization occurred correctly
        };


        return $factory->createProxy('EdsiTech\GandiBundle\Model\Domain', $initializer);
    }
}
