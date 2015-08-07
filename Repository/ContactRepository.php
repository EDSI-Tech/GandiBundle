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

use EdsiTech\GandiBundle\Factory\ContactFactory;
use EdsiTech\GandiBundle\Model\Contact;
use EdsiTech\GandiBundle\Service\ContactAPI;

class ContactRepository
{
    /**
     * @var DomainFactory
     */
    private $factory;

    /**
     * @var ContactAPI
     */
    private $api;

    /**
     * @param ContactFactory $factory
     * @param ContactAPI $contactAPI
     */
    public function __construct(ContactFactory $factory, ContactAPI $contactAPI)
    {
        $this->factory  = $factory;
        $this->api      = $contactAPI;
    }

    /**
     * @param string $handle
     * @return Contact
     */
    public function find($handle)
    {
        return $this->factory->build($handle);
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
     * @return Contact[]
     */
    public function findBy(array $options = null)
    {
        $objects = [];

        $$objectsList = $this->api->getList($options);

        foreach ($objectsList as $object) {
            $objects[] = $this->factory->build($object);
        }

        return $objectsList;
    }

    /**
     * Register a new contact
     *
     * @param Contact $contact
     * @return int $operationId
     */
    public function create(Contact $contact)
    {
        return $this->api->persist($contact);
    }

    /**
     * Commit a Contact changes
     *
     * @param Contact $contact
     * @return int number of changes
     */
    public function update(Contact $contact)
    {

        return $this->api->persist($contact);
    }
    
    /**
     * Delete a contact
     *
     * @param Contact $contact
     * @return bool
     */
    public function delete(Contact $contact)
    {

        return $this->api->delete($contact);
    }
    
    /**
     * Commit a Contact changes
     *
     * @param Contact $contact
     * @return int number of changes
     */
    public function persist(Contact $contact)
    {

        return $this->api->persist($contact);
    }

}
