# DHL Shipment Status module for OXID eShop

[![MIT license](https://img.shields.io/badge/License-MIT-blue.svg)](https://lbesson.mit-license.org/) [![Maintenance](https://img.shields.io/badge/Maintained%3F-yes-green.svg)](https://GitHub.com/Naereen/StrapDown.js/graphs/commit-activity)

Let users view the shipment status of deliveries under their user account in the shop.
#  Requirements
- OXID: ^6
- PHP: ^7.0 || ^8.0

# Installation
To install the module run the following command from the root directory of your OXID installation.
```console
composer require tremendo/dhlstatus
```

# Activation
After installing the module, you need to activate it, either via OXID eShop admin or CLI.
```console
./bin/oe-console oe:module:activate tremendo_dhlstatus
```
# Configuration
* Register an account at [https://developer.dhl.com](https://developer.dhl.com) and create a new app.
* Add the "Shipment Tracking - Unified" API to the newly created appp to get an API-key.
* Enter the key in the module's settings section.

# License
This module is licensed under the [MIT License](./LICENSE.md).