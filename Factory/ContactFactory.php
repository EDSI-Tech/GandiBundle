<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\Factory;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;
use EdsiTech\GandiBundle\Service\ContactAPI;
use EdsiTech\GandiBundle\Model\Contact;

class ContactFactory
{
    /**
     * @var ContactAPI
     */
    private $contactAPI;

    public function __construct(ContactAPI $contactAPI)
    {
        $this->contactAPI = $contactAPI;
    }

    /**
     * @param $contactHandle
     * @return Contact proxy object
     */
    public function build($contactHandle)
    {
        $factory     = new LazyLoadingValueHolderFactory();
        $initializer = function (&$wrappedObject, LazyLoadingInterface $proxy, $method, array $parameters, & $initializer) use ($contactHandle) {
            $initializer = null; // disable further initialization

            $result = $this->contactAPI->getInfo($contactHandle);

            $wrappedObject = (new Contact($contactHandle))
                
                ->setStreet($result['streetaddr'])
                ->setEmail($result['email'])
                ->setCountry($result['country'])
                ->setId($result['id'])
            ;

            return true; // confirm that initialization occurred correctly
        };


        return $factory->createProxy('EdsiTech\GandiBundle\Model\Contact', $initializer);
    }
}
