It adds new features to the built-in Magento's Elasticsearch module.

## How to install
```         
bin/magento maintenance:enable
rm -rf composer.lock
composer clear-cache
composer require mage2pro/elasticsearch:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/*
bin/magento setup:di:compile
bin/magento cache:enable
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f en_US <additional locales>
bin/magento maintenance:disable
```

## How to upgrade
```
bin/magento maintenance:enable
composer remove mage2pro/elasticsearch
rm -rf composer.lock
composer clear-cache
composer require mage2pro/elasticsearch:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/*
bin/magento setup:di:compile
bin/magento cache:enable
rm -rf pub/static/*
bin/magento setup:static-content:deploy -f en_US <additional locales>
bin/magento maintenance:disable
```