## Documentation

- Installation guide: https://www.magemoto.com/install-magento-2-extension/ 
- User Guide: https://docs.magemoto.com/shop-by-brand-m2/index.html
- Product page: https://www.magemoto.com/magento-2-shop-by-brand/
- FAQs: https://www.magemoto.com/faqs/
- Get Support: https://magemoto.freshdesk.com/ or support@magemoto.com
- Changelog: https://www.magemoto.com/releases/shop-by-brand
- License agreement: https://www.magemoto.com/LICENSE.txt


## How to install

Install ready-to-paste package (Recommended)

- Installation guide: https://www.magemoto.com/install-magento-2-extension/#solution-1-ready-to-paste


## How to upgrade

1. Backup
Backup your Magento code, database before upgrading.
2. Remove Shopbybrand folder 
In case of customization, you should backup the customized files and modify in newer version. 
Now you remove `app/code/MageMoto/Shopbybrand` folder. In this step, you can copy override Shopbybrand folder but this may cause of compilation issue. That why you should remove it.
3. Upload new version
Upload this package to Magento root directory
4. Run command line:

```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```



## FAQs



#### Q: My site is down
A: Please follow this guide: https://www.magemoto.com/blog/magento-site-down.html

#### Q: How to insert brands in homepage, mega menu?
A: Please follow this guide: https://www.magemoto.com/magento-2-shop-by-brand/show-brand-on-home-page.html

#### Q: Shop by brand Alphabet
A: Please follow this guide: https://www.magemoto.com/magento-2-shop-by-brand/alphabet.html



## Support

- FAQs: https://www.magemoto.com/faqs/
- https://magemoto.freshdesk.com/
- support@magemoto.com


