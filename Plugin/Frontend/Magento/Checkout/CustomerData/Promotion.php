<?php
declare(strict_types=1);

namespace Mfdc\Challenge\Plugin\Frontend\Magento\Checkout\CustomerData;

use Magento\Catalog\Helper\Image;
use Magento\Checkout\CustomerData\Cart;
use Magento\Checkout\Model\Session\Proxy as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class Promotion
{
    /**
     * @param CheckoutSession $checkoutSession
     * @param Image $imageHelper
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected CheckoutSession $checkoutSession,
        protected Image $imageHelper,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * @param Cart $subject
     * @param $result
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function afterGetSectionData(
        \Magento\Checkout\CustomerData\Cart $subject,
        $result
    ) {
        $quote = $this->checkoutSession->getQuote();
        if ($quote->getItemsCount()) {
            $minicartUpsellList = [];
            foreach ($quote->getAllVisibleItems() as $item) {
                $minicartUpsell = $item->getProduct()->getData('minicart_upsell');
                if ($minicartUpsell) {
                    try {
                        $upsellProduct = $item->getProduct()->loadByAttribute('sku', $minicartUpsell);
                        if (!$quote->hasProductId($upsellProduct->getId())) {
                            $minicartUpsellList[] = $upsellProduct;

                        }
                    } catch (NoSuchEntityException $ex) {
                        $this->logger->warning(__('Product sku: %1 has a wrong minicart_upsell attribute, the sku does not exist!', $item->getSku()));
                    }
                }
            }

            if (count($minicartUpsellList)) {
                $result['promotion'] = [];
                foreach ($minicartUpsellList as $upsell) {
                    $result['promotion'][] = [
                        "name" => $upsell->getName(),
                        "image" => $this->imageHelper->init($upsell, 'product_thumbnail_image')->getUrl(),
                        "url" => $upsell->getProductUrl()
                    ];
                }
            }
        }

        return $result;
    }
}
