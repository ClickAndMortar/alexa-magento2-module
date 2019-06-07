<?php

namespace ClickAndMortar\Alexa\Intent;

use Magento\Reports\Model\ResourceModel\Order\CollectionFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class GetAverageOrderIntent extends AbstractIntent
{
    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var StoreManagerInterface */
    private $storeManager;

    public function __construct(
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct();

        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function getText(): string
    {
        /** @var Store $store */
        $store = $this->storeManager->getStore();
        $currentCurrencyCode = $store->getBaseCurrency();

        $collection = $this->collectionFactory->create()->calculateSales();
        $collection->load();
        $sales = $collection->getFirstItem();

        $averageFormatted = $currentCurrencyCode->format(
            $sales->getData('average'),
            ['locale' => 'fr_FR'],
            false
        );

        $texts = [
            'Vous avez un panier moyen de %s depuis le lancement du site.',
            'Il y a un panier moyen de %s depuis le lancement du site.',
            'Le panier moyen est de %s depuis le lancement du site.',
        ];

        return sprintf($this->getRandomText($texts), $averageFormatted);
    }
}
