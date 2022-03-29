# Change Log

## [3.3.10] - 2022-03-29
### Added
- Support for Magento 2.4.4
- Support for Php 8.0/8.1

## [3.3.9] - 2021-09-08
### Changed
- Switch to Laminas Mime package, minimum Magento version is now 2.3.5
### Fixed
- Don't send the same named attachment twice

## [3.3.8] - 2021-07-30
### Added
- Support for Magento 2.4.3

## [3.3.7] - 2021-04-30
### Added
- Support for Magento 2.3.7

## [3.3.6] - 2021-02-02
### Added
- Support for Magento 2.4.2

## [3.3.5] - 2020-10-02
### Added
- Support for Magento 2.3.6 and 2.4.1

## [3.3.4] - 2020-07-28
### Added
- Support for PHP 7.4
- Support for Magento 2.4.0
### Fixed
- Mislabeled admin setting

## [3.3.3] - 2020-05-02
### Fixed
- Reworked email identification to strictly cover supported types only

## [3.3.2] - 2020-04-22
### Added
- Support for Magento 2.3.5

## [3.3.1] - 2020-01-24
### Added
- Support for Magento 2.3.4

## [3.3.0] - 2020-01-21
### Added
- Ability to attach invoices to the shipping confirmation email
### Changed
- Updated comments for latest Magento Coding Standards

## [3.2.0] - 2019-10-05
### Changed
- Refactor for new email handling in Magento 2.3.3
- Use previous releases for earlier versions of Magento
- Removed support for Php 7.0

## [3.1.9] - 2019-10-03
### Added
- Support for Magento 2.3.2-p1

## [3.1.8] - 2019-08-16
### Changed
- Adjust for changed core behaviour around plain text emails

## [3.1.7] - 2019-06-26
### Added
- Support for Magento 2.3.2 and 2.2.9

## [3.1.6] - 2019-05-10
### Changed
- Adopt latest Magento Coding Standards

## [3.1.5] - 2019-04-11
### Fixed
- Reverse additional return types

## [3.1.4] - 2019-04-09
### Fixed
- Reverse adding return types to maintain 2.2.8 compatibility

## [3.1.3] - 2019-03-28
### Added
- Compatibility with Magento 2.2.8

## [3.1.2] - 2019-03-27
### Added
- Compatibility with Magento 2.3.1
- Initial MFTF acceptance test

## [3.1.1] - 2019-01-04
### Added
- Readme
### Changed
- Reverse 7.1 features as Magento Marketplace does not yet support it

## [3.1.0] - 2018-11-28
### Changed
- Add compatibility with Magento 2.3.0 and handle upgrade of Zend_Mail, for earlier versions of Magento use
previous versions
- Use newer php features (minimum 7.1)
### Added
- Ability to customise affect the final filename

## [3.0.4] - 2018-11-06
### Changed
- Reorganised unit tests

## [3.0.3] - 2018-07-20
### Changed
- Code Quality improvement - use class constants
### Fixed
- Failing integration test for replaced pdfs

## [3.0.2] - 2018-06-25
### Changed
- Major rewrite - removed all preferences, use plugins on TransportBuilder and TransportFactory instead

## [3.0.1] - 2018-03-20
### Changed
- Adjusted tests to provide for Pdf Customiser transforming T&Cs to Pdfs

## [3.0.0] - 2018-03-16
### Changed
- Package changed into a Metapackage - Implementation moved into fooman/emailattachments-implementation-m2 package
- Semantic versioning will only be applied to the implementation package
- Attachments are also added to emails sent separately

## [2.1.0] - 2017-09-01
### Added
- Support for PHP 7.1
- Support for Magento 2.2.0

## [2.0.8] - 2017-06-02
### Fixed
- Make CheckoutAgreements dependency explicit

## [2.0.7] - 2017-02-28
### Added
- Ability for integration test to check for attachment name

## [2.0.6] - 2017-02-26
### Fixed
- Translations of file names (thanks Manuel)

## [2.0.5] - 2016-09-22
### Added
- Add note to "Attach Order as Pdf" that it requires the Print Order Pdf extension

## [2.0.4] - 2016-06-15
### Changed
- Widen dependencies in preparation for Magento 2.1.0

## [2.0.3] - 2016-04-03
### Fixed
- Add missing configuration setting for attaching T&Cs to shipping email

## [2.0.2] - 2016-04-01
### Changed
- Release for Marketplace

## [2.0.1] - 2016-03-25
### Added
- Integration tests now support Pdf Customiser supplied attachments

## [2.0.0] - 2016-01-21
### Changed
- Change project folder structure to src/ and tests/ (not applicable for Marketplace version)

## [1.0.0] - 2015-11-29
### Added
- Initial release for Magento 2
