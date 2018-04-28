<?php

require_once __DIR__ . '/../vendor/autoload.php';

$client = new \Eureka\EurekaClient([
    'eurekaDefaultUrl' => 'http://localhost:8761/eureka',
    'hostName' => 'test.hamid.work',
    'appName' => 'test',
    'ip' => '127.0.0.1',
    'port' => ['8080', true],
    'homePageUrl' => 'http://localhost:8080',
    'statusPageUrl' => 'http://localhost:8080/info',
    'healthCheckUrl' => 'http://localhost:8080/health'
]);

try {
    $client->register();
    $url = $client->fetchInstance("test")->homePageUrl;
    var_dump($url);
}
catch (\Eureka\Exceptions\EurekaClientException $e) {
    echo $e->getMessage();
}