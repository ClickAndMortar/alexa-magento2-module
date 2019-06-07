<?php

namespace ClickAndMortar\Alexa\Controller;

use ClickAndMortar\Alexa\Api\AlexaApplicationInterface;
use Magento\Framework\App\FrontControllerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\HTTP\PhpEnvironment\Request as HttpRequest;

class FrontController implements FrontControllerInterface
{
    public const REQUEST_TYPE_INTENT_REQUEST = 'IntentRequest';
    public const REQUEST_TYPE_LAUNCH_REQUEST = 'LaunchRequest';
    public const REQUEST_TYPE_SESSION_ENDED_REQUEST = 'SessionEndedRequest';

    /** @var ResultFactory */
    private $resultFactory;

    /** @var AlexaApplicationInterface */
    private $handler;

    public function __construct(
        ResultFactory $resultFactory,
        AlexaApplicationInterface $handler
    ) {
        $this->resultFactory = $resultFactory;
        $this->handler = $handler;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(RequestInterface $request): ResultInterface
    {
        /** @var HttpRequest $request */
        $requestContent = $request->getContent();
        $data = $this->getResultData(json_decode($requestContent, true));

        return $this->createResultJson($data);
    }

    /**
     * @param array $requestContent
     * @return array
     */
    private function getResultData(array $requestContent): array
    {
        $request = $requestContent['request'];
        $requestType = $request['type'];

        $resultData = [];
        $resultData['version'] = '1.0';

        if ($requestType === self::REQUEST_TYPE_LAUNCH_REQUEST) {
            $resultData['response'] = $this->handler->launchRequest()->getJson();
        }

        if ($requestType === self::REQUEST_TYPE_INTENT_REQUEST) {
            $resultData['response'] = $this->handler->intentRequest($request)->getJson();
        }

        if ($requestType === self::REQUEST_TYPE_SESSION_ENDED_REQUEST) {
            $resultData['response'] = $this->handler->sessionEndedRequest($request['reason'])->getJson();
        }

        return $resultData;
    }

    /**
     * @param array $data
     * @return Json
     */
    private function createResultJson(array $data): Json
    {
        /** @var Json $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setHttpResponseCode(200);
        $result->setHeader('Content-Type', 'application/json', true);
        $result->setData($data);

        return $result;
    }
}
