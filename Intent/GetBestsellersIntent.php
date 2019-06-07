<?php

namespace ClickAndMortar\Alexa\Intent;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Reports\Model\Item;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory as BestSellersCollectionFactory;

class GetBestsellersIntent extends AbstractIntent
{
    /**
     * @var BestSellersCollectionFactory
     */
    protected $bestSellersCollectionFactory;

    public function __construct(
        BestSellersCollectionFactory $bestSellersCollectionFactory
    ) {
        parent::__construct();

        $this->bestSellersCollectionFactory = $bestSellersCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        $size = 3;

        $bestSellers = $this->bestSellersCollectionFactory->create();
        $bestSellers->setPageSize($size);

        $bestSellersFormatted = [];

        /** @var Item $product */
        foreach ($bestSellers as $product) {
            $productName = $product->getData('product_name');
            $quantityOrdered = $product->getData('qty_ordered') * 1;

            $bestSellersFormatted[] = $productName . ' avec un nombre de ' . $quantityOrdered;
        }

        if (empty($bestSellersFormatted)) {
            return 'Il n\'y a pas de produits vendus.';
        }

        $texts = [
            sprintf('Les %d produits les plus vendus sont : ', $bestSellers->getSize()),
            sprintf('Les %d produits les mieux vendus sont : ', $bestSellers->getSize()),
        ];

        return $this->getRandomText($texts) . implode(', ', $bestSellersFormatted) . '.';
    }
}
