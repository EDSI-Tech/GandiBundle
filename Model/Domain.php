<?php
    
/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */
    
namespace EdsiTech\GandiBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Domain
{
    protected $contacts;

    protected $nameservers;

    protected $duration;

    protected $tld;

    protected $fqdn;


    //used only when registered (getters only)

    protected $id;

    protected $status;

    protected $autorenew;

    protected $authinfo;

    protected $created;

    protected $updated;

    protected $date_registry_end;

    protected $lock;

    /**
     * @var array used to track changes
     */
    private $changesTrack = [
        'auto_renew' => false
    ];
        
    const TYPE_LOCKED = 1;
    const TYPE_UNLOCKED = 0;

    public function __construct($domain)
    {
        $this->fqdn = $domain;

        $this->nameservers = array();
        $this->contacts = array(
            'bill' => null,
            'tech' => null,
            'admin' => null,
            'owner' => null,
            'reseller' => null,
        );

    }

    public function __toString() {
        
        return $this->fqdn ?: '';
    }

    public function toGandiArray() {

        return array(
            'admin' => $this->getAdminContact()->getHandle(),
            'bill' => $this->getBillContact()->getHandle(),
            'owner' => $this->getOwnerContact()->getHandle(),
            'tech' => $this->getTechContact()->getHandle(),
            'duration' => $this->getDuration(),
            'nameservers' => $this->getNameservers(),
        );

    }

    public function unlock() {
        $this->lock = self::TYPE_UNLOCKED;
    }

    public function lock() {
        $this->lock = self::TYPE_LOCKED;
    }

    public function setId($id) {

        $this->id = $id;

        return $this;
    }

    public function getId() {

        return $this->id;
    }

    public function setDuration($duration) {

        $this->duration = $duration;

        return $this;

    }

    public function getDuration() {

        return $this->duration;

    }

    /**
     * @return array
     */

    public function getNameservers() {

        return $this->nameservers;
    }

    /**
     * @param Array $nameservers
     * @return $this
     */

    public function setNameservers(array $nameservers) {

        $this->nameservers = $nameservers;

        return $this;
    }

    /**
     * @param $nameserver
     * @return $this
     */

    public function addNameserver($nameserver) {

        $this->nameservers[] = $nameserver;

        return $this;
    }

    /**
     * @param $nameserver
     * @return $this
     */

    public function removeNameserver($nameserver) {

        foreach($this->nameservers as $key => $value) {

            if($value == $nameserver) {
                unset($this->nameservers[$key]);
            }

        }

        return $this;
    }

    /**
     * @param Contact $contact
     * @return $this
     */

    public function setOwnerContact(Contact $contact) {

        $this->contacts['owner'] = $contact;

        return $this;

    }

    /**
     * @return Contact
     */

    public function getOwnerContact() {

        return $this->contacts['owner'];
    }

    /**
     * @param Contact $contact
     * @return $this
     */

    public function setResellerContact(Contact $contact) {

        $this->contacts['reseller'] = $contact;

        return $this;

    }

    /**
     * @return Contact
     */

    public function getResellerContact() {

        return $this->contacts['reseller'];
    }

    /**
     * @param Contact $contact
     * @return $this
     */

    public function setAdminContact(Contact $contact) {

        $this->contacts['admin'] = $contact;

        return $this;

    }

    /**
     * @return Contact
     */

    public function getAdminContact() {

        return $this->contacts['admin'];
    }


    /**
     * @param Contact $contact
     * @return $this
     */

    public function setTechContact(Contact $contact) {

        $this->contacts['tech'] = $contact;

        return $this;

    }

    /**
     * @return Contact
     */

    public function getTechContact() {

        return $this->contacts['tech'];
    }

    /**
     * @param Contact $contact
     * @return $this
     */

    public function setBillContact(Contact $contact) {

        $this->contacts['bill'] = $contact;

        return $this;

    }

    /**
     * @return Contact
     */

    public function getBillContact() {

        return $this->contacts['bill'];
    }

    /**
     * @return String
     */

    public function getFqdn() {

        return $this->fqdn;
    }

    /**
     * @return String
     */

    public function getAuthInfo() {

        return $this->authinfo;
    }

    /**
     * @param String $authinfo
     * @return $this
     */
    public function setAuthInfo($authinfo) {

        $this->authinfo = $authinfo;

        return $this;
    }

    /**
     * @return String
     */

    public function getStatus() {

        return $this->status;
    }

    /**
     * @param String $status
     * @return $this
     */
    public function setStatus($status) {

        $this->status = $status;

        return $this;
    }

    /**
     * @return String
     */

    public function getAutorenew() {

        return $this->autorenew;
    }

    /**
     * @param String $autorenew
     * @return $this
     */
    public function setAutorenew($autorenew)
    {
        if ($autorenew !== $this->autorenew) {
            $this->changesTrack['auto_renew'] = true;
        }

        $this->autorenew = $autorenew;

        return $this;
    }

    public function getLock() {

        return $this->lock;
    }

    /**
     * @return String
     */

    public function getCreated() {

        return $this->created;
    }

    /**
     * @param String $created
     * @return $this
     */
    public function setCreated(\DateTime $created) {

        $this->created = $created;

        return $this;
    }
    
    /**
     * @return String
     */

    public function getUpdated() {

        return $this->updated;
    }

    /**
     * @param String $created
     * @return $this
     */
    public function setUpdated(\DateTime $updated) {

        $this->updated = $updated;

        return $this;
    }

    /**
     * @return array
     */
    public function getChangesTrack()
    {
        return $this->changesTrack;
    }
        
}