<?php

namespace App\Abstracts;

use App\Interfaces\AggregatorInterface;

abstract class AbstractAggregator implements AggregatorInterface
{
    protected $apiKey;

    protected $baseUrl;
}