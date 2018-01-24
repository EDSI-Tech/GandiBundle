# EDSI-Tech Gandi Bundle

Gandi API v3


## Installation

1. Install the bundle with composer

`composer require edsi-tech/gandi-bundle`

2. Register the Bundle

Add in your AppKernel.php  `new EdsiTech\GandiBundle\EdsiTechGandiBundle(),`

3. Add the mandatory configurations options

In your app/config/config.yml :

```yaml
 edsitech_gandi:
    server_url: "https://rpc.ote.gandi.net/xmlrpc/"
    api_key: 
    default_nameservers:
        - dns1.edsi-tech.com
        - dns2.edsi-tech.com
        - dns3.edsi-tech.com
    default_handles:
        bill: XYZ-GANDI
        tech: XYZ-GANDI
        admin: XYZ-GANDI
        owner: XYZ-GANDI
        
```

Note that the test server is `https://rpc.ote.gandi.net/xmlrpc/` and the production server is `https://rpc.gandi.net/xmlrpc/`

## Usage examples

### Get all domains names and expiration date

```php
$currentHandle = "MYHANDLE-GANDI";

$domainRepository = $this->get('edsitech_gandi.domain_repository');
$domains_api = $domainRepository->findBy(['handle' => $currentHandle]);
foreach($domains_api as $domain) {
    $fqdn = $domain->getFqdn();
    echo $fqdn.": ".$domain->getExpire()->format('Y-m-d')."\n";
}
```

### Get a domain name
```php
use EdsiTech\GandiBundle\Model\Domain;

$fqdn = "example.com";

$domainRepository = $this->get('edsitech_gandi.domain_repository');
$domain = $domainRepository->find($fqdn);

if($domain instanceof Domain) {
    print_r($domain);
}

```

### Get available extensions

```php
$gandi = $this->get('edsitech_gandi.domain_availibility');
print_r($gandi->getExtensions());
```
### Check domain availibility

```php
$domain = "example.com";

$domainAPI = $this->get('edsitech_gandi.domain_availibility');
$domain = idn_to_ascii($domain); //needed for special chars domains 
        
$result = $domainAPI->isAvailable([$domain]); //this is an array, you can pass multiple domains

print_r($result); //the result is also an array, the key is the domain name and the value is the result.
```

### Register a domain name

```php
use EdsiTech\GandiBundle\Model\Domain;
use EdsiTech\GandiBundle\Model\Contact;
use EdsiTech\GandiBundle\Model\Operation;
use EdsiTech\GandiBundle\Exception\APIException;

$fqdn = "example.com";
$myHandle = "XYZ-GANDI";

$domain = new Domain($fqdn);
$domain->setDuration(1) //years
       ->setAutorenew(true)
       ->setOwnerContact(new Contact($myHandle))
       ;
//others contact informations are taken from the default_handles config keys.

$domainRepository = $this->get('edsitech_gandi.domain_repository');

try {
    $result = $domainRepository->register($domain);
     
     if($result instanceof Operation) {
         
         echo "Operation in progress";
         
     }

                 
 } catch (APIException $e) {
     
     $message = $e->getMessage();
     
     echo "Error: ".$message;

 }

```

### Transfer a domain name
```php
use EdsiTech\GandiBundle\Model\Operation;

$domain = "example.com";
$authcode = "test";

$domainRepository = $this->get('edsitech_gandi.domain_repository');
$result = $domainRepository->transfert($domain, $authcode);

if($result instanceof Operation) {
    echo "Operation in progress";
}
                
```
### Enable auto-renew
```php
use EdsiTech\GandiBundle\Exception\APIException;

$fqdn = "example.com";

$domainRepository = $this->get('edsitech_gandi.domain_repository');
     
$domain = $domainRepository->find($fqdn);

try {
    $domainRepository->enableAutorenew($domain);
} catch (APIException $e) {

    echo "That's an error";

}
```

### Disable lock on domain
```php
use EdsiTech\GandiBundle\Exception\APIException;

$fqdn = "example.com";

$domainRepository = $this->get('edsitech_gandi.domain_repository');
     
$domain = $domainRepository->find($fqdn);

try {
    $domain->setLock(false);
    $domainRepository->update($domain);
    
} catch (APIException $e) {

    echo "That's an error";

}
```



### Create a Symfony2 form for the contact
```php
use EdsiTech\GandiBundle\Form\ContactType;
use EdsiTech\GandiBundle\Model\Contact;
use EdsiTech\GandiBundle\Exception\APIException;

$contactRepository = $this->get('edsitech_gandi.contact_repository');

$contact = $contactRepository->find($currentHandle);

$form = $this->createForm(new ContactType(), $contact);


//....



if ($form->isValid()) {

    try {

        $result = $contactRepository->persist($contact);
        
                    
    } catch (APIException $e) {
        

		//...

    }
}

```

## Disclamer

* The API requires some extras informations for specific tld that are not implemented in this bundle.
* The DNSSEC support is in development and not fully implemented.
* You may need to add a cache layer (like redis for instance) to the domain and extension lists as it take 10-15 seconds to get the results.

## Testing

Run `bin/phpunit Tests/`