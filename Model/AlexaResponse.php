<?php

namespace ClickAndMortar\Alexa\Model;

class AlexaResponse
{
    /** @var array */
    private $json;

    public function __construct()
    {
        $this->json = [];
        $this->setShouldEndSession(true);
    }

    /**
     * @param string $text
     * @param string $type
     */
    public function setOutputSpeech($text, $type = 'PlainText'): void
    {
        $this->json['outputSpeech'] = ['text' => $text, 'type' => $type];
    }

    /**
     * @param bool $endSession
     */
    public function setShouldEndSession($endSession): void
    {
        $this->json['shouldEndSession'] = $endSession;
    }

    /**
     * @return array
     */
    public function getJson(): array
    {
        return $this->json;
    }
}
