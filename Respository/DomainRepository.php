<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
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

    public function __construct(DomainFactory $factory, DomainAPI $domainAPI)
    {
        $this->factory  = $factory;
        $this->api      = $domainAPI;
    }


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
     * @return Domain[]
     */
    public function findBy(array $options)
    {
        $domainObjects = [];

        $domainsList = $this->api->getList($options);

        foreach ($domainsList as $domainName) {
            $domainObjects[] = $this->factory->build($domainName);
        }

        return $domainObjects;
    }

    public function add(Domain $domain)
    {
    }
}
