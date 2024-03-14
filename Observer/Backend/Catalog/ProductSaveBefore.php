<?php
declare(strict_types=1);

namespace Mfdc\Challenge\Observer\Backend\Catalog;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;

class ProductSaveBefore implements ObserverInterface
{
    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected ManagerInterface $messageManager
    ) {
    }

    /**
     * In this observer we are checking if the sku added into 'minicart_upsell' exists as a product
     * If not then we redirect back with error message
     *
     * @param Observer $observer
     * @return void
     * @throws NoSuchEntityException
     */
    public function execute(
        Observer $observer
    ) {
        /** @var $product \Magento\Catalog\Model\Product */
        $product = $observer->getEvent()->getProduct();
        $minicartUpsellOrig = $product->getOrigData('minicart_upsell');
        $minicartUpsell = $product->getData('minicart_upsell');
        if ($minicartUpsellOrig !== $minicartUpsell) {
            try {
                $this->productRepository->get($minicartUpsell);
            } catch (NoSuchEntityException $ex) {
                $this->messageManager->addError(__("Saving sku: %1 in attribute 'Minicart up-sell Product' but the product does not exist, we reverted that change.", $minicartUpsell));
                $product->setData('minicart_upsell', $minicartUpsellOrig);
            }
        }
    }
}

