﻿# BarionPHP

[![version](https://img.shields.io/badge/version-2.0.0-blue.svg)](https://packagist.org/packages/barion/barion-web-php) [![Total Downloads](https://poser.pugx.org/barion/barion-web-php/downloads.svg)](https://packagist.org/packages/barion/barion-web-php) [![License](https://poser.pugx.org/barion/barion-web-php/license.svg)](https://packagist.org/packages/barion/barion-web-php)

**BarionPHP** is a compact PHP library to manage online e-money and card payments via the _Barion Smart Gateway_.  
It allows you to accept credit card, e-money, and wire transfer payments in just a few lines of code.

**BarionPHP** lets you

- Start an online payment easily in various scenarios (immediate payment, reservation/escrow, delayed capture, third-party payments, recurring/token payment etc.)
- Get details about a given payment
- Finish an ongoing reservation payment completely or partially, with automatic refund support
- Refund a completed payment transaction completely or partially

All with just a few simple pieces of code!

# Version history

- **2.0.0** XX. XX. 2024
- **1.4.11** April 2. 2024
- **1.4.10** June 13. 2022
- **1.4.9** June 9. 2022
- **1.4.8** June 9. 2022
- **1.4.7** May 25. 2022
- **1.4.6** April 28. 2021
- **1.4.5** April 15. 2021
- **1.4.4** February 17. 2021
- **1.4.3** December 11. 2020
- **1.4.2** August 15. 2019.
- **1.4.1** August 14. 2019.
- **1.4.0** August 08. 2019.
- **1.3.2** August 05. 2019.
- **1.3.1** March 20. 2019.
- **1.3.0** March 12. 2019.
- **1.2.9** May 16. 2017.
- **1.2.8** April 13. 2017.
- **1.2.7** February 14. 2017.
- **1.2.5** November 07. 2016.
- **1.2.4** May 25. 2016.
- **1.2.3** January 14. 2016.
- **1.2.2** January 11. 2016.
- **1.1.0** November 27. 2015.
- **1.0.1** November 26. 2015.
- **1.0.0** November 17. 2015.

For details about version changes, please refer to the [changelog.md](https://github.com/barion/barion-web-php/blob/master/changelog.md) file.

# System requirements

- PHP 8.2 or higher
- cURL module enabled (at least v7.18.1)
- SSL enabled (systems using OpenSSL with the version of 0.9.8f at least)

## Legacy version support

If you are using PHP versions 8.1 or lower, download our library version [1.4.11](https://github.com/Adyen/adyen-php-api-library/releases/tag/1.4.11).

# Installation

## Composer

You can install the library via [Composer](http://getcomposer.org/). Run the following command:

```
composer require barion/barion-web-php
```

Then generate the necessary [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading) files using Composer.  
The library automatically detects Composer-generated autoload files and uses them for autoloading.

## Manual installation

If you do not wish to use Composer, just copy the contents of the **barion** library into the desired folder.  
The library will use its own autoloading mechanism if no Composer-generated files are detected.

> **Note**  
> Be sure to set the necessary access to the path when running your PHP script.

# Basic usage

Include the **BarionClient** class in your PHP script:

```php
require_once 'library/BarionClient.php';
```

Then instantiate a Barion client. To achieve this, you must supply three parameters.

First, the secret key of the online store registered in Barion (called POSKey)

```php
$myPosKey = "9c165cfc-cbd1-452f-8307-21a3a9cee664";
```

The API version number (2 by default)

```php
$apiVersion = 2;
```

The environment to connect to. This can be the test system, or the production environment.

```php
// Test environment
$environment = BarionEnvironment::Test;

// Production environment
$environment = BarionEnvironment::Prod;
```

With these parameters you can create an instance of the **BarionClient** class:

```php
$BC = new BarionClient(poskey: $myPosKey, version: $apiVersion, env: $environment, useBundledRootCerts: false);
```

> **Note**  
> Only set `useBundledRootCerts` to `true` as a last resort if you are having SSL connection problems. It is your own responsibility to use up-to-date software that includes the latest international trusted CA certificate chains. The authors take no responsibility for loss of service resulting from the use of the bundled certificates.

# Base flow

Using the library, managing a payment process consists of two steps:

## 1\. Starting the payment

### 1.1. Creating the request object

To start an online payment, you have to create one or more **Payment Transaction** objects, add transaction **Items** to them, then group these transactions together in a **Payment** object.

First, create an **ItemModel**:

```php
$item = new ItemModel();
$item->Name = "TestItem";
$item->Description = "A test product";
$item->Quantity = 1;
$item->Unit = "piece";
$item->UnitPrice = 1000;
$item->ItemTotal = 1000;
$item->SKU = "ITEM-01";
```

Then create a **PaymentTransactionModel** and add the **Item** mentioned above to it:

```php
$trans = new PaymentTransactionModel();
$trans->POSTransactionId = "TRANS-01";
$trans->Payee = "webshop@example.com";
$trans->Total = 1000;
$trans->Comment = "Test transaction containing the product";
$trans->AddItem($item);
```

Finally, create a **PreparePaymentRequestModel** and add the **PaymentTransactionModel** mentioned above to it:

```php
$ppr = new PreparePaymentRequestModel();
$ppr->GuestCheckout = true;
$ppr->PaymentType = PaymentType::Immediate;
$ppr->FundingSources = array(FundingSourceType::All);
$ppr->PaymentRequestId = "PAYMENT-01";
$ppr->PayerHint = "user@example.com";
$ppr->Locale = UILocale::EN;
$ppr->OrderNumber = "ORDER-0001";
$ppr->Currency = Currency::HUF;
$ppr->RedirectUrl = "http://webshop.example.com/afterpayment";
$ppr->CallbackUrl = "http://webshop.example.com/processpayment";
$ppr->AddTransaction($trans);
```

**Note:** the secret _POSKey_ used for authentication is not part of the request object.  
The Barion client class automatically injects this value into every request sent to the Barion API.

### 1.2. Calling the Barion API

Now you can call the **PreparePayment** method of the Barion client with the request model you just created:

```php
$myPayment = $BC->PreparePayment($ppr);
```

The Barion API now prepares a payment entity that can be paid by anyone.

The **$myPayment** variable holds the response received from the Barion API, which is an instance of a **PreparePaymentResponseModel** object.

### 1.3. Redirecting the user to the Barion Smart Gateway

You can use the **PaymentId** value in the response to redirect the user to the Barion Smart Gateway. You have to supply this identifier in the **Id** query string parameter.  
The complete redirect URL looks like this:

```
https://secure.barion.com/Pay?id=<your_payment_id>
```

The user can now complete the payment at the Barion Smart Gateway.

## 2\. Getting information about a payment

In this example we are going to get detailed information about the payment we just created above.

### 2.1. Creating the request object

To request details about a payment, you only need one parameter: the payment identifier. This is the **PaymentId** we have used earlier to redirect the user.

```
64157032-d3dc-4296-aeda-fd4b0994c64e
```

### 2.2. Calling the Barion API

To request payment details, we call the **GetPaymentState** method of the Barion client class with the identifier above:

```php
$paymentDetails = $BC->GetPaymentState("64157032-d3dc-4296-aeda-fd4b0994c64e");
```

Based on the payment status and parameters received in the response, the shop can now decide whether the payment was successful or not.

# API documentation

To help fully understand the Barion API, please refer to the official [Barion API Documentation](https://docs.barion.com).

# Basic troubleshooting

Here are a few common mistakes you might want to double check for before reaching out to our support:

**1\. I get a "User authentication failed" error when sending my request**

- Check if you are sending the correct POSkey to the correct environment, e.g. if you want to call the API in the TEST environment, use the POSkey of the shop that you registered on the TEST website.
- Check if the sent data is actually a valid JSON string, without any special characters, delimiters, line-breaks or invalid encoding.

**2\. I get a "Shop is closed" error message in the TEST environment**

- Check if your shop is open after logging in to the Barion Test website. Please note that you must fill out every data of your shop and then send it to approval. After this, approval will automatically be completed and your shop will be in Open status. This only applies to the TEST environment.

**3\. I get SSL errors about invalid certificates when trying to call the API**

- Be sure to double check your server certificates and the issuer's trusted status. Always renew your server certificates in time to avoid loss of service.
- Check every single component of your architecture for outdated software. Be sure to use the latest PHP, other runtimes, and even operating systems whenever possible. Trusted certificate chains may expire or get revoked, and Barion has no control over them. Using End-Of-Service and End-Of-Life software is highly discouraged.
- As a last resort, you may try creating the `BarionClient` instance with `useBundledRootCerts` set to `true`. But be advised, there is no guarantee that the bundled certificate chain remains valid at all times.

# License

This repository is available under the [Apache 2.0 License](https://github.com/barion/barion-web-php/blob/master/LICENSE).

# Further examples

To view more examples about the usage of the Barion library, refer to the _docs_ and _examples_ folders of the repository.

_© 2024 Barion Payment Inc._
