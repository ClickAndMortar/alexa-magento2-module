<?php

namespace ClickAndMortar\Alexa\Intent;

interface IntentInterface
{
    /**
     * @param array $slots
     */
    public function setSlots(array $slots): void;

    /**
     * @return string
     */
    public function getText(): string;
}
