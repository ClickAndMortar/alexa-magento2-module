<?php

namespace ClickAndMortar\Alexa\Intent;

use Magento\Reports\Model\ResourceModel\Order\CollectionFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class GetTurnoverForPeriodIntent extends AbstractIntent
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
        $slotsValues = $this->getSlotValues($this->slots);

        $period = $slotsValues['period']['resolved'];
        $periods = $this->getPeriods();

        if (!isset($periods[$period])) {
            return $this->getTurnover();
        }

        $collection = $this->collectionFactory->create()->addCreateAtPeriodFilter($periods[$period]['periodFilter']);
        $collection->calculateTotals();
        $collection->load();
        $totals = $collection->getFirstItem();

        /** @var Store $store */
        $store = $this->storeManager->getStore();
        $currentCurrencyCode = $store->getBaseCurrency();
        $lifetimeFormatted = $currentCurrencyCode->format(
            $totals->getData('revenue'),
            ['locale' => 'fr_FR'],
            false
        );

        $texts = [
            'Vous avez un chiffre d\'affaires pour %s de %s.',
            'Il y a un chiffre d\'affaires pour %s de %s.',
            'Le chiffre d\'affaires pour %s est de %s.',
        ];

        return sprintf($this->getRandomText($texts), $periods[$period]['periodText'], $lifetimeFormatted);
    }

    /**
     * @return string
     */
    private function getTurnover(): string
    {
        $collection = $this->collectionFactory->create()->calculateSales();
        $collection->load();
        $sales = $collection->getFirstItem();

        /** @var Store $store */
        $store = $this->storeManager->getStore();
        $currentCurrencyCode = $store->getBaseCurrency();
        $lifetimeFormatted = $currentCurrencyCode->format(
            $sales->getData('lifetime'),
            ['locale' => 'fr_FR'],
            false
        );

        $texts = [
            'Vous avez un chiffre d\'affaires de %s depuis le lancement du site.',
            'Il y a un chiffre d\'affaires de %s depuis le lancement du site.',
            'Le chiffre d\'affaires est de %s depuis le lancement du site.',
        ];

        return sprintf($this->getRandomText($texts), $lifetimeFormatted);
    }
}
