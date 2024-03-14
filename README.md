# Magento 2 Module - Magento Frontend Developer Code Challenge

    victorchiriac89/mfdc-challenge

## Main Functionalities

Product added through custom product attribute as up-sell addition on bottom section of minicart

## Installation

### Step 1: Unzip file

 - download the code and unzip into `app/code/Mfdc/Challenge` of your magento 2 project

### Step 2: Enable

 - `bin/magento module:enable Mfdc_Challenge`
 - `bin/magento setup:upgrade --keep-generated`
 - `bin/magento setup:di:compile`
 - `bin/magento setup:static-content:deploy -f` (-f not needed in production)
 - `bin/magento cache:clean`
 - `bin/magento cache:flush`

## Configuration

 - login into your magento 2 admin panel
 - for every product there is now an attribute called `minicart_upsell` labeled "Minicart up-sell Product SKU"
 - you are able to use one product sku for every product that will be added in minicart as up-sell
 - once added to the cart it will no longer be advertised in minicart

## Specifications

 - Observer <b>catalog_product_save_before</b> (Scope Backend)
   - `Mfdc\Challenge\Observer\Backend\Catalog\ProductSaveBefore`
 - Patch where we add the product attribute:
   - `Mfdc\Challenge\Setup\Patch\Data\AddMinicartUpsellProductAttribute`
 - Plugin (Scope Frontend) for <b>Magento\Checkout\CustomerData\Cart</b> where we do the check for `minicart_upsell` and pass the information into the getSectionData used in templates:
   - `Mfdc\Challenge\Plugin\Frontend\Magento\Checkout\CustomerData\Promotion`

## Attributes

 - Product - Minicart up-sell Product SKU (minicart_upsell)
