<?php

/*
 * (c) EDSI-Tech Sarl - All rights reserved.
 * This file cannot be copied and/or distributed without express permission of EDSI-Tech Sarl and all its content remains the property of EDSI-Tech Sarl.
 */

namespace EdsiTech\GandiBundle\API;

use Zend\XmlRpc\Client;

class DomainAvailability
{
    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var Client
     */
    protected $gandi;

    const MAX_TIMEOUT = 5;

    public function __construct($server_url, $api_key)
    {
        $this->api_key = $api_key;
        $this->gandi = new Client($server_url);
    }

    /**
     * @return array
     */
    public function getExtensions()
    {
        $gandi = $this->gandi->getProxy('domain.tld');

        $extensions = $gandi->list($this->api_key);

        $data = array();

        foreach($extensions as $ext) {

            if('golive' == $ext['phase'] && 'all' == $ext['visibility']) {
                $data[] = $ext['name'];
            }

        }

        return $data;
    }

    /**
     * @param array $domains domain names
     * @param array $options
     * @return mixed
     */
    public function isAvailable(array $domainNames, array $options = null)
    {
        $maxRetry = 0;

        $gandi = $this->gandi->getProxy('domain');

        $results = $gandi->available($this->api_key, $domainNames, $options);

        foreach($results as $domain => $result) {
            if('pending' == $result) {
                $maxRetry++;

                //if max retry has expired, return the data as it.
                if($maxRetry > self::MAX_TIMEOUT) {
                    return $results;
                }

                sleep(1);
                return $this->isAvailable($domainNames);
            }
        }

        return $results;
    }
}
