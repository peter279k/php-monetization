# php-monetization

## Introduction

- This is a PHP for Web Monetization helper.

## Features

Here are some features about Web Monetization:

- Validate payment pointer URL.
- Generate payment pointer URL with given arguments.
- Generate payment pointer URL with meta tag.
- Get default payment pointer URL.

## Installation

We strongly recoomend using `composer` to install this package.

The installation steps are as follows:

```
composer require lee/php-monetization:^1.0
```

## Usage

It's very simple. Here are some usages:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Lee\WebMonetization\WebMonetization;

// Generate payment pointer URL with meta tag
$paymentPointerUrl = '$ilp.uphold.com/alice';
$metaTag = WebMonetization::generateMetaTag($paymentPointerUrl);

echo $metaTag; // <meta name="monetization" content="$ilp.uphold.com/alice">

// Validate payment pointer URL
$validateResult = WebMonetization::validatePaymentPointer($paymentPointerUrl);

echo $validateResult; // true

// Generate payment pointer
$interLedger = 'ilp';
$domainName = 'uphold';
$tldName = 'com';
$userTokenName = 'alice';
$paymentPointerUrl = WebMonetization::generatePaymentPointer($interLedger, $domainName, $tldName, $userTokenName);

echo $paymentPointerUrl; // $ilp.uphold.com/alice

// Get default payment pointer urls
$webMonetization = new WebMonetization();
$paymentPointerUrls = $webMonetization->getDefaultPaymentPointerUrls();

echo $paymentPointerUrls; // [ '$ilp.uphold.com/', '$ilp.gatehub.net/', '$pay.stronghold.co/',]
```
