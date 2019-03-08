# Kurepay Payment Gateway


### Requirement
PHP 5.3.0 or more recent

### Install
composer require osinakayah/kurepay-gateway

### Usage
After making a call to getTransactionUrl it returns a url, do a redirect to that url after successful
payment the user is redirected back to the url provided on the admin dashboard

### Demo
```php
<?php
/**
 * User: osinakayah
 * Date: 08/03/2019
 * Time: 7:02 AM
 */

$kurepay = new \Kurepay\KurepayGateway(PUBLIC_KEY);
$redirectUrl = '';
try {
    $redirectUrl = $kurepay->getTransactionUrl($email, $amount, $reference, $fullname, $phoneNumber, ['item' => "Extra meta data"]);
}
catch (\Exception $exception){
    die($e->getMessage());
}
//Takes the user to payment page.
header('Location: ' . $redirectUrl);

```
### Verify Transaction

```php
<?php
/**
 * User: osinakayah
 * Date: 08/03/2019
 * Time: 7:02 AM
 */

$kurepay = new \Kurepay\KurepayGateway(PUBLIC_KEY);
$wasPaymentSuccessful = false;
try {
     $wasPaymentSuccessful = $kurepay->getTransactionStatus($reference);
}
catch (\Exception $exception){
    die($e->getMessage());
}

if ($wasPaymentSuccessful) {
    echo 'Payment was successful';
}
else {
    echo 'Nah, it was not successful';
}

```

