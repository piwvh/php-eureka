<?php

namespace Eureka;

use Eureka\Discovery\RandomStrategy;
use Eureka\Interfaces\DiscoveryStrategy;
use Eureka\Interfaces\InstanceProvider;

class EurekaConfig {

    private $eurekaDefaultUrl = 'http://localhost:8761';
    private $hostName;
    private $appName;
    private $ip;
    private $status = 'UP';
    private $overriddenStatus = 'UNKNOWN';
    private $port;
    private $securePort = ['443', false];
    private $countryId = '1';
    private $dataCenterInfo = ['com.netflix.appinfo.InstanceInfo$DefaultDataCenterInfo', 'MyOwn' /* keyword */];
    private $homePageUrl;
    private $statusPageUrl;
    private $healthCheckUrl;
    private $vipAddress;
    private $secureVipAddress;

    private $heartbeatInterval = 30;

    /**
     * @var DiscoveryStrategy
     */
    private $discoveryStrategy;

    /**
     * @var InstanceProvider
     */
    private $instanceProvider;

    // constructor
    public function __construct($config) {
        foreach ($config as $key => $value) {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        // defaults
        if(empty($this->hostName)) {
            $this->hostName = $this->ip;
        }
        if(empty($this->vipAddress)) {
            $this->vipAddress = $this->appName;
        }
        if(empty($this->secureVipAddress)) {
            $this->secureVipAddress = $this->appName;
        }
        if(empty($this->discoveryStrategy)) {
            $this->discoveryStrategy = new RandomStrategy();
        }
    }

    // getters
    public function getEurekaDefaultUrl() {
        return $this->eurekaDefaultUrl;
    }

    public function getHostName() {
        return $this->hostName;
    }

    public function getAppName() {
        return $this->appName;
    }

    public function getIp() {
        return $this->ip;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getOverriddenStatus() {
        return $this->overriddenStatus;
    }

    public function getPort() {
        return $this->port;
    }

    public function getSecurePort() {
        return $this->securePort;
    }

    public function getCountryId() {
        return $this->countryId;
    }

    public function getDataCenterInfo() {
        return $this->dataCenterInfo;
    }

    public function getHomePageUrl() {
        return $this->homePageUrl;
    }

    public function getStatusPageUrl() {
        return $this->statusPageUrl;
    }

    public function getHealthCheckUrl() {
        return $this->healthCheckUrl;
    }

    public function getVipAddress() {
        return $this->vipAddress;
    }

    public function getSecureVipAddress() {
        return $this->secureVipAddress;
    }

    public function getHeartbeatInterval() {
        return $this->heartbeatInterval;
    }

    public function getDiscoveryStrategy() {
        return $this->discoveryStrategy;
    }

    public function getInstanceProvider() {
        return $this->instanceProvider;
    }

    // setters
    public function setEurekaDefaultUrl($eurekaDefaultUrl) {
        $this->eurekaDefaultUrl = $eurekaDefaultUrl;
    }

    public function setHostName($hostName) {
        $this->hostName = $hostName;
    }

    public function setAppName($appName) {
        $this->appName = $appName;
    }

    public function setIp($ip) {
        $this->ip = $ip;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setOverriddenStatus($overriddenStatus) {
        $this->overriddenStatus = $overriddenStatus;
    }

    public function setPort($port) {
        $this->port = $port;
    }

    public function setSecurePort($securePort) {
        $this->securePort = $securePort;
    }

    public function setCountryId($countryId) {
        $this->countryId = $countryId;
    }

    public function setDataCenterInfo($dataCenterInfo) {
        $this->dataCenterInfo = $dataCenterInfo;
    }

    public function setHomePageUrl($homePageUrl) {
        $this->homePageUrl = $homePageUrl;
    }

    public function setStatusPageUrl($statusPageUrl) {
        $this->statusPageUrl = $statusPageUrl;
    }

    public function setHealthCheckUrl($healthCheckUrl) {
        $this->healthCheckUrl = $healthCheckUrl;
    }

    public function setVipAddress($vipAddress) {
        $this->vipAddress = $vipAddress;
    }

    public function setSecureVipAddress($secureVipAddress) {
        $this->secureVipAddress = $secureVipAddress;
    }

    public function setHeartbeatInterval($heartbeatInterval) {
        $this->heartbeatInterval = $heartbeatInterval;
    }

    public function setDiscoveryStrategy(DiscoveryStrategy $discoveryStrategy) {
        $this->discoveryStrategy = $discoveryStrategy;
    }

    public function setInstanceProvider(InstanceProvider $instanceProvider) {
        $this->instanceProvider = $instanceProvider;
    }

    //
    public function getRegistrationConfig() {
        return [
            'instance' => [
                'instanceId' => $this->getInstanceId(),
                'hostName' => $this->hostName,
                'app' => $this->appName,
                'ipAddr' => $this->ip,
                'status' => $this->status,
                'overriddenstatus' => $this->overriddenStatus,
                'port' => [
                    '$' => $this->port[0],
                    '@enabled' => $this->port[1]
                ],
                'securePort' => [
                    '$' => $this->securePort[0],
                    '@enabled' => $this->securePort[1]
                ],
                'countryId' => $this->countryId,
                'dataCenterInfo' => [
                    '@class' => $this->dataCenterInfo[0],
                    'name' => $this->dataCenterInfo[1]
                ],
                'homePageUrl' => $this->homePageUrl,
                'statusPageUrl' => $this->statusPageUrl,
                'healthCheckUrl' => $this->healthCheckUrl,
                'vipAddress' => $this->vipAddress,
                'secureVipAddress' => $this->secureVipAddress
            ]
        ];
    }

    public function getInstanceId() {
        return $this->hostName . ':' . $this->appName . ':' . $this->port[0];
    }

}