
---

# DevAll_Payze Module

The DevAll_Payze module is a Magento 2 integration for the [Payze](https://payze.io/en) service.

## Installation

1. Install the module via Composer by running the following command in the Magento 2 root directory:

   ```
   composer require developersalliance/module-payze
   ```

2. Run the Magento upgrade command to install the module:

   ```
   bin/magento setup:upgrade
   ```

## Features

- Module currently only supports paying by JustCard method of payze
- It also supports refunding orders
- Webhook is implemented but it only responds to refund events


## License

This module is licensed under the [MIT License](LICENSE.txt).

---