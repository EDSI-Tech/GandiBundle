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

            $result = $this->contactAPI->get($contactHandle);

            $wrappedObject = (new Contact($contactHandle))
                ->setId($result['id'])
                ->setCompany($result['orgname'])
                ->setType($result['type'])
                ->setVatNumber($result['vat_number'])
                ->setFirstName($result['given'])
                ->setLastName($result['family'])
                ->setStreet($result['streetaddr'])
                ->setZip($result['zip'])
                ->setCity($result['city'])
                ->setCountry($result['country'])
                ->setEmail($result['email'])
                ->setPhone($result['phone'])
                ->setMobile($result['mobile'])
                ->setFax($result['fax'])
                ->setLanguage($result['lang'])
                ->setHideAddress($result['data_obfuscated'])
                ->setHideEmail($result['mail_obfuscated'])
            ;

            return true; // confirm that initialization occurred correctly
        };


        return $factory->createProxy('EdsiTech\GandiBundle\Model\Contact', $initializer);
    }
}
