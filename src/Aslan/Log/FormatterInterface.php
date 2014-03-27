<?php

namespace Aslan\Log;

interface FormatterInterface
{
    public function format($message, $type, $timestamp, array $context = null);
}