<?php
/**
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 *
 * @author      Philippe BONVIN <p.bonvin@edsi-tech.com>
 * @version     1.0
 * @since       2015-08-22
 */

namespace EdsiTech\GandiBundle\Repository;

use EdsiTech\GandiBundle\Factory\DomainFactory;
use EdsiTech\GandiBundle\Model\Domain;
use EdsiTech\GandiBundle\Service\DomainAPI;

class DomainRepository
{
    /**
     * @var DomainFactory
     */
    private $factory;

    /**
     * @var DomainAPI
     */
    private $api;

    /**
     * @param DomainFactory $factory
     * @param DomainAPI $domainAPI
     */
    public function __construct(DomainFactory $factory, DomainAPI $domainAPI)
    {
        $this->factory  = $factory;
        $this->api      = $domainAPI;
    }

    /**
     * @param $domainName
     * @return Domain
     */
    public function find($domainName)
    {
        return $this->factory->build($domainName);
    }

    /**
     * @return Domain[]
     */
    public function findAll()
    {
        return $this->findBy([]);
    }

    /**
     * @param array|null $options
     * @return Domain[]
     */
    public function findBy(array $options = null)
    {
        $domainObjects = [];
        
        $domainsList = $this->api->getList($options);

        foreach ($domainsList as $domainName) {
            $domain = idn_to_utf8($domainName->getFqdn());
            $domainObjects[] = $this->factory->build($domain);
        }

        return $domainObjects;
    }

    /**
     * Register a new domain name
     *
     * @param Domain $domain
     * @return Operation $operation
     */
    public function register(Domain $domain)
    {
        return $this->api->register($domain);
    }
    
    /**
     * Transfer a domain name
     *
     * @param Domain $domain
     * @return Operation $operation
     */
    public function transfert(Domain $domain, $authcode = null, $change_owner = false, $duration = 1)
    {
        return $this->api->transfert($domain, $authcode, $change_owner, $duration);
    }

    /**
     * Commit a Domain changes
     *
     * @param Domain $domain
     * @return int number of changes
     */
    public function update(Domain $domain)
    {
        $changes = $domain->getChangesTrack();

        $nbUpdates = 0;

        //update autorenew settings
        if (true === $changes['auto_renew']) {
            if (true === $domain->getAutorenew()) {
                $this->api->enableAutorenew($domain);
            } else {
                $this->api->disableAutorenew($domain);
            }

            $nbUpdates++;
        }
        
        //update nameservers
        if (true === $changes['nameservers']) {
            $this->api->setNameservers($domain);
        }
        
        //update lock status
        if (true === $changes['lock']) {
            
            if(false === $domain->getLock()) {
                $this->api->unlock($domain);
            } else {
                $this->api->lock($domain);
            }
            
        }
        
        
        //update dnssec key
        if (true === $changes['dnssec']) {
            
            //@TODO: complete
            //essayer ici de trouver les différences...
            
        }

        return $nbUpdates;
    }

    /**
     * @param Domain $domain
     * @return bool
     */
    public function enableAutorenew(Domain $domain) {
        
        return $this->api->enableAutorenew($domain);
        
    }
}
