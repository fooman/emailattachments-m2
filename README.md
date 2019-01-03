# Fooman Email Attachments 
## Extension for Magento 2

A User Manual can be found (here) [https://magento2-support.fooman.co.nz/collection/1001-email-attachments-magento-2]

## Installation

This package is available via packagist.org. Please use Composer to install the extension

```
bin/magento deploy:mode:set developer (if you are in production mode)
composer require fooman/emailattachments-m2
bin/magento module:enable --clear-static-content Fooman_EmailAttachments
bin/magento setup:upgrade

your usual sequence of commands to enable production mode, for example
bin/magento deploy:mode:set production
```

## Depending on Email Attachments 
If you are using Email Attachments to build functionality on top of please require the implmentation package
`composer require emailattachments-implementation-m2` instead as only that package will be semantically versioned.