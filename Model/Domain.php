<?php
/**
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 *
 * @author      Philippe BONVIN <p.bonvin@edsi-tech.com>
 * @version     1.0
 * @since       2015-08-18
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

    protected $dnssec;

    //used only when registered (getters only)

    protected $id;

    protected $status;

    protected $autorenew;

    protected $authinfo;

    protected $created;

    protected $updated;

    protected $expire;

    protected $lock;

    /**
     * @var array used to track changes
     */
    private $changesTrack = [
        'auto_renew' => false,
        'nameservers' => false,
        'lock' => false,
        'dnssec' => false,
    ];
        
    const TYPE_LOCKED = 1;
    const TYPE_UNLOCKED = 0;

    /**
     * @param string $domain
     */
    public function __construct($domain)
    {
        $this->fqdn = $domain;

        $this->nameservers = array();
        $this->dnssec = array();
        $this->contacts = array(
            'bill' => null,
            'tech' => null,
            'admin' => null,
            'owner' => null,
            'reseller' => null,
        );

    }

    /**
     * @return string
     */
    public function __toString() {
        
        return $this->fqdn ?: '';
    }

    /**
     * @return array
     */
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

    /**
     * unlock domain for transfert
     */
    public function unlock() {
        $this->lock = self::TYPE_UNLOCKED;
    }

    /**
     * lock domain
     */
    public function lock() {
        $this->lock = self::TYPE_LOCKED;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id) {

        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId() {

        return $this->id;
    }

    /**
     * @param string $duration
     * @return $this
     */
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

        $this->changesTrack['nameservers'] = true;

        $this->nameservers = $nameservers;

        return $this;
    }

    /**
     * @param $nameserver
     * @return $this
     */
    public function addNameserver($nameserver) {

        $this->changesTrack['nameservers'] = true;
        
        $this->nameservers[] = $nameserver;

        return $this;
    }

    /**
     * @param $nameserver
     * @return $this
     */
    public function removeNameserver($nameserver) {

        $this->changesTrack['nameservers'] = true;

        foreach($this->nameservers as $key => $value) {

            if($value == $nameserver) {
                unset($this->nameservers[$key]);
            }

        }

        return $this;
    }
    
    /**
     * @param Array $dnssec
     * @return $this
     */
    public function setDnssec(array $dnssec) {

        $this->changesTrack['dnssec'] = true;

        $this->dnssec = $dnssec;

        return $this;
    }

    /**
     * @param $dnssec
     * @return $this
     */
    public function addDnssec($dnssec) {

        $this->changesTrack['dnssec'] = true;

        $this->dnssec[] = $dnssec;

        return $this;
    }

    /**
     * @param $dnssec
     * @return $this
     */
    public function removeDnssec($dnssec) {
        
        $this->changesTrack['dnssec'] = true;

        foreach($this->dnssec as $key => $value) {

            if($value == $dnssec) {
                unset($this->dnssec[$key]);
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
     * @return this
     */
    public function setTld($tld) {

        $this->tld = $tld;
        
        return $this;
    }
    
    /**
     * @return String
     */
    public function getTld() {
        
        if($this->tld) {
            return $this->tld;
        } else {
            return strstr($this->fqdn,'.');
        }
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

    public function setLock($lock) {

        $this->changesTrack['lock'] = true;

        $this->lock = $lock;
        
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
     * @return String
     */

    public function getExpire() {

        return $this->expire;
    }

    /**
     * @param String $expire
     * @return $this
     */
    public function setExpire(\DateTime $expire) {

        $this->expire = $expire;

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