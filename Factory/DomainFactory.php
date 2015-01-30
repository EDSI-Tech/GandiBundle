<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\Factory;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;
use EdsiTech\GandiBundle\Service\DomainAPI;
use EdsiTech\GandiBundle\Model\Domain;

class DomainFactory
{
    /**
     * @var DomainAPI
     */
    private $domainAPI;

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
            $initializer   = null; // disable further initialization

            $result = []; // TODO: fetch info from API

            $wrappedObject = (new Domain($domainName))
                ->setAuthInfo($result['authinfo'])
                ->setNameservers($result['nameservers'])
                ->setAutorenew($result['autorenew']['active'])
                ->setCreated(new \DateTime($result['date_created']))
            ;

            return true; // confirm that initialization occurred correctly
        };


        return $factory->createProxy('EdsiTech\GandiBundle\Model\Domain', $initializer);
    }
}
