# Greenrivers MaintenanceMode

Magento2 module maintenance mode.

## Requirements

* PHP >= **7.1**
* Magento >= **2.3**

## Installation

1. Module

    ```shell    
   composer require greenrivers/maintenance-mode
   
    php bin/magento module:enable Greenrivers_MaintenanceMode
    
    php bin/magento setup:upgrade
    
    php bin/magento setup:di:compile
    
    php bin/magento setup:static-content:deploy -f
    ```

## Usage

#### **Stores->Configuration->GREENRIVERS->Maintenance Mode**

* **General->Enabled** - module activation


* **Settings->Frontend** - enable maintenance in frontend
* **Settings->Backend** - enable maintenance in backend
* **Settings->Whitelist ips** - ip list exclude from maintenance mode


* **Custom page->Enabled** - enable custom page in maintenance mode
* **Custom page->Styles** - css rules for custom page
* **Custom page->Content** - custom page content


* **Cron->Frontend->Enabled** - enable cronjob for maintenance mode in frontend
* **Cron->Frontend->Frequency** - cron frequency for maintenance in frontend
* **Cron->Frontend->Time start** - cron enable maintenance mode in frontend
* **Cron->Frontend->Time end** - cron disable maintenance mode in frontend


* **Cron->Backend->Enabled** - enable cronjob for maintenance mode in backend
* **Cron->Backend->Frequency** - cron frequency for maintenance in backend
* **Cron->Backend->Time start** - cron enable maintenance mode in backend
* **Cron->Backend->Time end** - cron disable maintenance mode in backend


* **Cron->Whitelist ips** - ip list exclude from cron maintenance mode
