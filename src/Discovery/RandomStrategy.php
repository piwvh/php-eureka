<?php

namespace Eureka\Discovery;

use Eureka\Exceptions\InstanceFailureException;
use Eureka\Interfaces\DiscoveryStrategy;
use GuzzleHttp\Client as GuzzleClient;

class RandomStrategy implements DiscoveryStrategy {

    public function getInstance($instances) {
        $random = rand(0, count($instances) - 1);

        return $instances[$random];
    }
}