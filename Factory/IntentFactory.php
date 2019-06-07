<?php

namespace ClickAndMortar\Alexa\Factory;

use ClickAndMortar\Alexa\Intent\GetAverageOrderIntent;
use ClickAndMortar\Alexa\Intent\GetBestsellersIntent;
use ClickAndMortar\Alexa\Intent\GetCustomersNowOnlineIntent;
use ClickAndMortar\Alexa\Intent\GetOrdersCountForPeriodIntent;
use ClickAndMortar\Alexa\Intent\GetTurnoverForPeriodIntent;
use ClickAndMortar\Alexa\Intent\IntentInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Customer\Model\ResourceModel\Online\Grid\Collection as OnlineGridCollection;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Reports\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory as BestSellersCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class IntentFactory
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var OrderCollectionFactory */
    private $collectionFactory;

    /** @var StoreManagerInterface */
    private $storeManager;

    /**
     * @var BestSellersCollectionFactory
     */
    private $bestSellersCollectionFactory;

    /**
     * @var OnlineGridCollection
     */
    protected $onlineGridCollection;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderCollectionFactory $orderCollectionFactory,
        StoreManagerInterface $storeManager,
        BestSellersCollectionFactory $bestSellersCollectionFactory,
        OnlineGridCollection $onlineGridCollection
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionFactory = $orderCollectionFactory;
        $this->storeManager = $storeManager;
        $this->bestSellersCollectionFactory = $bestSellersCollectionFactory;
        $this->onlineGridCollection = $onlineGridCollection;
    }

    /**
     * @param string $intentName
     * @param array $slots
     * @return IntentInterface|null
     */
    public function create(string $intentName, array $slots = []): ?IntentInterface
    {
        if ($intentName === 'GetAverageOrder') {
            return new GetAverageOrderIntent(
                $this->collectionFactory,
                $this->storeManager
            );
        }

        if ($intentName === 'GetOrdersCountForPeriod') {
            $intent = new GetOrdersCountForPeriodIntent(
                $this->orderRepository,
                $this->searchCriteriaBuilder,
                $this->collectionFactory
            );

            $intent->setSlots($slots);

            return $intent;
        }

        if ($intentName === 'GetTurnoverForPeriod') {
            $intent = new GetTurnoverForPeriodIntent(
                $this->collectionFactory,
                $this->storeManager
            );

            $intent->setSlots($slots);

            return $intent;
        }

        if ($intentName === 'GetBestsellers') {
            $intent = new GetBestsellersIntent(
                $this->bestSellersCollectionFactory
            );

            return $intent;
        }

        if ($intentName === 'GetCustomersNowOnline') {
            $intent = new GetCustomersNowOnlineIntent(
                $this->onlineGridCollection
            );

            return $intent;
        }

        return null;
    }
}
