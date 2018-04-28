<?php

namespace Eureka\Interfaces;

interface DiscoveryStrategy {

    public function getInstance($instances);

}